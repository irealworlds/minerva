<?php

declare(strict_types=1);

namespace App\ApplicationServices\StudentDisciplineEnrolments\ListByStudent;

use App\Core\Contracts\Cqrs\IQueryHandler;
use App\Core\Models\StudentDisciplineEnrolment;
use App\Core\Models\StudentGroupEnrolment;
use App\Core\Optional;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Enumerable;

/**
 * @implements IQueryHandler<ListStudentDisciplineEnrolmentsQuery, Enumerable<int, StudentDisciplineEnrolment>>
 */
final readonly class ListStudentDisciplineEnrolmentsHandler implements
    IQueryHandler
{
    /**
     * @inheritDoc
     */
    public function __invoke(mixed $query): Enumerable
    {
        $queryBuilder = StudentDisciplineEnrolment::query();

        // Add the student registration filter
        $queryBuilder = $this->addStudentRegistrationFilter(
            queryBuilder: $queryBuilder,
            studentRegistrationKey: $query->studentRegistrationKey,
        );

        // Add the discipline filter
        $queryBuilder = $this->addDisciplineFilter(
            queryBuilder: $queryBuilder,
            disciplineKey: $query->disciplineKey,
        );

        return $queryBuilder->get();
    }

    /**
     * @param Builder<StudentDisciplineEnrolment> $queryBuilder
     * @return Builder<StudentDisciplineEnrolment>
     */
    public function addStudentRegistrationFilter(
        Builder $queryBuilder,
        mixed $studentRegistrationKey,
    ): Builder {
        return $queryBuilder->whereHas(
            (new StudentDisciplineEnrolment())
                ->studentGroupEnrolment()
                ->getRelationName(),
            static function (Builder $studentGroupEnrolmentQuery) use (
                $studentRegistrationKey,
            ) {
                return $studentGroupEnrolmentQuery->where(
                    (new StudentGroupEnrolment())
                        ->studentRegistration()
                        ->getForeignKeyName(),
                    $studentRegistrationKey,
                );
            },
        );
    }

    /**
     * @param Optional<mixed> $disciplineKey
     * @param Builder<StudentDisciplineEnrolment> $queryBuilder
     * @return Builder<StudentDisciplineEnrolment>
     */
    public function addDisciplineFilter(
        Builder $queryBuilder,
        mixed $disciplineKey,
    ): Builder {
        if ($disciplineKey->hasValue()) {
            $queryBuilder = $queryBuilder->where(
                (new StudentDisciplineEnrolment())
                    ->discipline()
                    ->getForeignKeyName(),
                $disciplineKey->value,
            );
        }
        return $queryBuilder;
    }
}
