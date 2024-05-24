<?php

namespace App\ApplicationServices\Institutions\ListFilteredPaginated;

use App\Core\Contracts\Cqrs\IQueryHandler;
use App\Core\Models\Institution;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

/**
 * @implements IQueryHandler<ListFilteredPaginatedInstitutionsQuery>
 */
final readonly class ListFilteredPaginatedInstitutionsHandler implements IQueryHandler
{
    /**
     * @inheritDoc
     */
    public function __invoke(mixed $query): LengthAwarePaginator
    {
        $queryBuilder = Institution::query();

        // Add the parent id filter
        if ($query->parentId->hasValue()) {
            $queryBuilder = $queryBuilder->where(
                (new Institution())->parent()->getForeignKeyName(),
                $query->parentId->getValue()
            );
        }

        // Add the search query filter
        if ($query->searchQuery->hasValue()) {
            if ($search = $query->searchQuery->getValue()) {
                $queryBuilder = $queryBuilder->where(function (Builder $searchQueryBuilder) use ($search) {
                    $searchQueryBuilder
                        ->where(
                            DB::raw("LOWER(name)"),
                            "like",
                            "%" . mb_strtolower($search, "UTF-8") . "%"
                        );
                });
            }
        }

        return $queryBuilder
            ->with(["parent", "children"])
            ->latest()
            ->paginate(
                perPage: $query->pageSize,
                page: $query->page
            );
    }
}
