<?php

declare(strict_types=1);

namespace App\ApplicationServices\Institutions\ListFilteredPaginated;

use App\Core\Contracts\Cqrs\IQueryHandler;
use App\Core\Models\Institution;
use Exception;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\DatabaseManager;
use Illuminate\Pagination\AbstractPaginator;

/**
 * @implements IQueryHandler<ListFilteredPaginatedInstitutionsQuery, LengthAwarePaginator<Institution> & AbstractPaginator<Institution>>
 */
final readonly class ListFilteredPaginatedInstitutionsHandler implements
    IQueryHandler
{
    public function __construct(private DatabaseManager $_db)
    {
    }

    /**
     * @inheritDoc
     *
     * @throws Exception
     */
    public function __invoke(
        mixed $query,
    ): AbstractPaginator&LengthAwarePaginator {
        $queryBuilder = Institution::query();

        // Add the parent id filter
        if ($query->parentId->hasValue()) {
            $queryBuilder = $queryBuilder->where(
                (new Institution())->parent()->getForeignKeyName(),
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
            ->with(['parent', 'children'])
            ->latest()
            ->paginate(perPage: $query->pageSize, page: $query->page);
    }
}
