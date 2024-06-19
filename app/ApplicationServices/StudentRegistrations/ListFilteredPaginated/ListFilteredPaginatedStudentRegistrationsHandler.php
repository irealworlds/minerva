<?php

declare(strict_types=1);

namespace App\ApplicationServices\StudentRegistrations\ListFilteredPaginated;

use App\Core\Contracts\Cqrs\IQueryHandler;
use App\Core\Models\StudentRegistration;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\DatabaseManager;
use Illuminate\Pagination\AbstractPaginator;
use InvalidArgumentException;

/**
 * @implements IQueryHandler<ListFilteredPaginatedStudentRegistrationsQuery, LengthAwarePaginator<StudentRegistration> & AbstractPaginator<StudentRegistration>>
 */
final readonly class ListFilteredPaginatedStudentRegistrationsHandler implements
    IQueryHandler
{
    public function __construct(private DatabaseManager $_db)
    {
    }

    /**
     * @inheritDoc
     *
     * @throws InvalidArgumentException
     */
    public function __invoke(
        mixed $query,
    ): AbstractPaginator&LengthAwarePaginator {
        $queryBuilder = StudentRegistration::query();

        // Add the search query filter
        if ($query->searchQuery->hasValue()) {
            if ($search = $query->searchQuery->value) {
                $queryBuilder = $queryBuilder->whereHas(
                    relation: (new StudentRegistration())
                        ->identity()
                        ->getRelationName(),
                    callback: function (Builder $identityQueryBuilder) use (
                        $search,
                    ): void {
                        $identityQueryBuilder->where(
                            $this->_db->raw(
                                'LOWER(first_name) + LOWER(last_name)',
                            ),
                            'like',
                            '%' . mb_strtolower($search, 'UTF-8') . '%',
                        );
                    },
                );
            }
        }

        // Return a paginated result
        return $queryBuilder
            ->latest()
            ->paginate(perPage: $query->pageSize, page: $query->page);
    }
}
