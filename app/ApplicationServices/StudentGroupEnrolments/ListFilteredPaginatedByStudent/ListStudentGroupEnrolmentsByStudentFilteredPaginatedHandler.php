<?php

declare(strict_types=1);

namespace App\ApplicationServices\StudentGroupEnrolments\ListFilteredPaginatedByStudent;

use App\Core\Contracts\Cqrs\IQueryHandler;
use App\Core\Models\StudentGroupEnrolment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\AbstractPaginator;
use InvalidArgumentException;

/**
 * @implements IQueryHandler<ListStudentGroupEnrolmentsByStudentFilteredPaginatedQuery, LengthAwarePaginator<StudentGroupEnrolment>&AbstractPaginator<StudentGroupEnrolment>>
 */
final readonly class ListStudentGroupEnrolmentsByStudentFilteredPaginatedHandler implements IQueryHandler
{
    /**
     * @inheritDoc
     * @throws InvalidArgumentException
     */
    public function __invoke(
        mixed $query,
    ): AbstractPaginator&LengthAwarePaginator {
        $queryBuilder = StudentGroupEnrolment::query();

        // Add the student registration key to the query
        $queryBuilder = $queryBuilder->where(
            (new StudentGroupEnrolment())
                ->studentRegistration()
                ->getForeignKeyName(),
            $query->studentRegistrationKey,
        );

        return $queryBuilder->paginate(
            perPage: $query->pageSize,
            page: $query->page,
        );
    }
}
