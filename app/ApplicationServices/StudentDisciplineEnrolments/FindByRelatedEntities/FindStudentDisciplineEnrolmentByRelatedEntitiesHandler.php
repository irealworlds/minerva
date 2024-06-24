<?php

declare(strict_types=1);

namespace App\ApplicationServices\StudentDisciplineEnrolments\FindByRelatedEntities;

use App\Core\Contracts\Cqrs\IQueryHandler;
use App\Core\Models\StudentDisciplineEnrolment;
use App\Core\Models\StudentGroupEnrolment;
use Illuminate\Database\Eloquent\Builder;

/**
 * @implements IQueryHandler<FindStudentDisciplineEnrolmentByRelatedEntitiesQuery, StudentDisciplineEnrolment|null>
 */
final readonly class FindStudentDisciplineEnrolmentByRelatedEntitiesHandler
    implements IQueryHandler
{
    /**
     * @inheritDoc
     */
    public function __invoke(mixed $query): StudentDisciplineEnrolment|null
    {
        $queryBuilder = StudentDisciplineEnrolment::query();

        // Add student group and registration filters
        $studentGroupKey = $query->studentGroupKey;
        $studentRegistrationKey = $query->studentRegistrationKey;

        $queryBuilder = $queryBuilder->whereHas(
            (new StudentDisciplineEnrolment())
                ->studentGroupEnrolment()
                ->getRelationName(),
            static function (Builder $studentDisciplineEnrolmentQuery) use (
                $studentRegistrationKey,
                $studentGroupKey,
            ) {
                return $studentDisciplineEnrolmentQuery
                    ->where(
                        (new StudentGroupEnrolment())
                            ->studentGroup()
                            ->getForeignKeyName(),
                        $studentGroupKey,
                    )
                    ->where(
                        (new StudentGroupEnrolment())
                            ->studentRegistration()
                            ->getForeignKeyName(),
                        $studentRegistrationKey,
                    );
            },
        );

        // Add discipline filter
        $queryBuilder = $queryBuilder->where(
            (new StudentDisciplineEnrolment())
                ->discipline()
                ->getForeignKeyName(),
            $query->disciplineKey,
        );

        // Add educator filter
        $queryBuilder = $queryBuilder->where(
            (new StudentDisciplineEnrolment())->educator()->getForeignKeyName(),
            $query->educatorKey,
        );

        return $queryBuilder->first();
    }
}
