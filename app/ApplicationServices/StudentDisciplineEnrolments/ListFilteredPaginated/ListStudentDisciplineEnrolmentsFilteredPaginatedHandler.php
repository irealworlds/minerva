<?php

declare(strict_types=1);

namespace App\ApplicationServices\StudentDisciplineEnrolments\ListFilteredPaginated;

use App\Core\Contracts\Cqrs\IQueryHandler;
use App\Core\Models\StudentDisciplineEnrolment;
use App\Core\Models\StudentGroupEnrolment;
use App\Core\Optional;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\AbstractPaginator;
use InvalidArgumentException;

/**
 * @implements IQueryHandler<ListStudentDisciplineEnrolmentsFilteredPaginatedQuery, LengthAwarePaginator<StudentDisciplineEnrolment>&AbstractPaginator<StudentDisciplineEnrolment>>
 */
final readonly class ListStudentDisciplineEnrolmentsFilteredPaginatedHandler
    implements IQueryHandler
{
    /**
     * @inheritDoc
     * @throws InvalidArgumentException
     */
    public function __invoke(
        mixed $query,
    ): LengthAwarePaginator&AbstractPaginator {
        $queryBuilder = StudentDisciplineEnrolment::query();

        $queryBuilder = $this->addDisciplineFilter(
            $queryBuilder,
            $query->disciplineKey,
        );

        $queryBuilder = $this->addEducatorKey(
            $queryBuilder,
            $query->educatorKey,
        );

        $queryBuilder = $this->addStudentGroupAndStudentRegistrationFilters(
            $queryBuilder,
            $query->studentGroupKey,
            $query->studentRegistrationKey,
        );

        return $queryBuilder->paginate(
            perPage: $query->pageSize,
            page: $query->page,
        );
    }

    /**
     * @param Builder<StudentDisciplineEnrolment> $queryBuilder
     * @param Optional<mixed> $disciplineKey
     * @return Builder<StudentDisciplineEnrolment>
     */
    private function addDisciplineFilter(
        Builder $queryBuilder,
        Optional $disciplineKey,
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

    /**
     * @param Builder<StudentDisciplineEnrolment> $queryBuilder
     * @param Optional<mixed> $educatorKey
     * @return Builder<StudentDisciplineEnrolment>
     */
    private function addEducatorKey(
        Builder $queryBuilder,
        Optional $educatorKey,
    ): Builder {
        if ($educatorKey->hasValue()) {
            $queryBuilder = $queryBuilder->where(
                (new StudentDisciplineEnrolment())
                    ->educator()
                    ->getForeignKeyName(),
                $educatorKey->value,
            );
        }

        return $queryBuilder;
    }

    /**
     * @param Builder<StudentDisciplineEnrolment> $queryBuilder
     * @param Optional<mixed> $studentGroupKey
     * @param Optional<mixed> $studentRegistrationKey
     * @return Builder<StudentDisciplineEnrolment>
     */
    private function addStudentGroupAndStudentRegistrationFilters(
        Builder $queryBuilder,
        Optional $studentGroupKey,
        Optional $studentRegistrationKey,
    ): Builder {
        if (
            $studentGroupKey->hasValue() ||
            $studentRegistrationKey->hasValue()
        ) {
            $queryBuilder = $queryBuilder->whereHas(
                (new StudentDisciplineEnrolment())
                    ->studentGroupEnrolment()
                    ->getRelationName(),
                static function (Builder $studentGroupEnrolmentQuery) use (
                    $studentRegistrationKey,
                    $studentGroupKey,
                ) {
                    // Add student group
                    if ($studentGroupKey->hasValue()) {
                        $studentGroupEnrolmentQuery = $studentGroupEnrolmentQuery->where(
                            (new StudentGroupEnrolment())
                                ->studentGroup()
                                ->getForeignKeyName(),
                            $studentGroupKey->value,
                        );
                    }

                    // Add student registration key
                    if ($studentRegistrationKey->hasValue()) {
                        $studentGroupEnrolmentQuery = $studentGroupEnrolmentQuery->where(
                            (new StudentGroupEnrolment())
                                ->studentRegistration()
                                ->getForeignKeyName(),
                            $studentRegistrationKey->value,
                        );
                    }

                    return $studentGroupEnrolmentQuery;
                },
            );
        }

        return $queryBuilder;
    }
}
