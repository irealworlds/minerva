<?php

declare(strict_types=1);

namespace App\ApplicationServices\StudentDisciplineGrades\List;

use App\Core\Contracts\Cqrs\IQueryHandler;
use App\Core\Models\StudentDisciplineGrade;
use App\Core\Optional;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Enumerable;

/**
 * @implements IQueryHandler<ListStudentDisciplineGradesQuery, Enumerable<int, StudentDisciplineGrade>>
 */
final readonly class ListStudentDisciplineGradesHandler implements IQueryHandler
{
    /**
     * @inheritDoc
     */
    public function __invoke(mixed $query): Enumerable
    {
        $queryBuilder = StudentDisciplineGrade::query();

        // Add the student registration filter
        $queryBuilder = $this->addStudentRegistrationFilter(
            $queryBuilder,
            $query->studentRegistrationKeys,
        );

        // Add the student group filter
        $queryBuilder = $this->addStudentGroupFilter(
            $queryBuilder,
            $query->studentGroupKeys,
        );

        // Add the discipline filter
        $queryBuilder = $this->addDisciplineFilter(
            $queryBuilder,
            $query->disciplineKeys,
        );

        return $queryBuilder
            ->orderBy('awarded_at')
            ->orderBy('created_at')
            ->get();
    }

    /**
     * @param Builder<StudentDisciplineGrade> $query
     * @param Optional<iterable<mixed>> $studentKeys
     * @return Builder<StudentDisciplineGrade>
     */
    protected function addStudentRegistrationFilter(
        Builder $query,
        Optional $studentKeys,
    ): Builder {
        if ($studentKeys->hasValue()) {
            $query = $query->whereIn(
                (new StudentDisciplineGrade())
                    ->studentRegistration()
                    ->getForeignKeyName(),
                $studentKeys->value,
            );
        }
        return $query;
    }

    /**
     * @param Builder<StudentDisciplineGrade> $query
     * @param Optional<iterable<mixed>> $studentGroupKeys
     * @return Builder<StudentDisciplineGrade>
     */
    protected function addStudentGroupFilter(
        Builder $query,
        Optional $studentGroupKeys,
    ): Builder {
        if ($studentGroupKeys->hasValue()) {
            $query = $query->whereIn(
                (new StudentDisciplineGrade())
                    ->studentGroup()
                    ->getForeignKeyName(),
                $studentGroupKeys->value,
            );
        }
        return $query;
    }

    /**
     * @param Builder<StudentDisciplineGrade> $query
     * @param Optional<iterable<mixed>> $disciplineKeys
     * @return Builder<StudentDisciplineGrade>
     */
    protected function addDisciplineFilter(
        Builder $query,
        Optional $disciplineKeys,
    ): Builder {
        if ($disciplineKeys->hasValue()) {
            $query = $query->whereIn(
                (new StudentDisciplineGrade())
                    ->discipline()
                    ->getForeignKeyName(),
                $disciplineKeys->value,
            );
        }
        return $query;
    }
}
