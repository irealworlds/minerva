<?php

declare(strict_types=1);

namespace App\ApplicationServices\StudentGroups\ListFilteredPaginated;

use App\Core\Contracts\Cqrs\IQueryHandler;
use App\Core\Models\StudentGroup;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\DatabaseManager;
use Illuminate\Pagination\AbstractPaginator;
use InvalidArgumentException;

/**
 * @implements IQueryHandler<ListFilteredPaginatedStudentGroupsQuery, LengthAwarePaginator<StudentGroup> & AbstractPaginator<StudentGroup>>
 */
final readonly class ListFilteredPaginatedStudentGroupsHandler implements
    IQueryHandler
{
    public function __construct(private DatabaseManager $_db)
    {
    }

    /**
     * @inheritDoc
     * @throws InvalidArgumentException
     */
    public function __invoke(
        mixed $query,
    ): AbstractPaginator&LengthAwarePaginator {
        $queryBuilder = StudentGroup::query();

        // Add the parent type filter
        if ($query->parentType->hasValue()) {
            $queryBuilder = $queryBuilder->where(
                'parent_type',
                $query->parentType->value,
            );
        }

        // Add the parent id filter
        if ($query->parentId->hasValue()) {
            $queryBuilder = $queryBuilder->where(
                (new StudentGroup())->parent()->getForeignKeyName(),
                $query->parentId->value,
            );
        }

        // Add the search query filter
        if ($query->searchQuery->hasValue()) {
            if ($search = $query->searchQuery->value) {
                $queryBuilder = $queryBuilder->where(function (
                    Builder $searchQueryBuilder,
                ) use ($search): void {
                    $searchQueryBuilder->where(
                        $this->_db->raw('LOWER(name)'),
                        'like',
                        '%' . mb_strtolower($search, 'UTF-8') . '%',
                    );
                });
            }
        }

        return $queryBuilder
            ->with(['parent', 'childGroups'])
            ->latest()
            ->paginate(perPage: $query->pageSize, page: $query->page);
    }
}
