<?php

declare(strict_types=1);

namespace App\ApplicationServices\Disciplines\ListFilteredPaginated;

use App\Core\Contracts\Cqrs\IQueryHandler;
use App\Core\Models\{Discipline, Institution};
use Exception;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\DatabaseManager;
use Illuminate\Pagination\AbstractPaginator;

/**
 * @implements IQueryHandler<ListFilteredPaginatedDisciplinesQuery, LengthAwarePaginator<Discipline> & AbstractPaginator<Discipline>>
 */
final readonly class ListFilteredPaginatedDisciplinesHandler implements
    IQueryHandler
{
    public function __construct(private DatabaseManager $_db)
    {
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function __invoke(
        mixed $query,
    ): AbstractPaginator&LengthAwarePaginator {
        $queryBuilder = Discipline::query();

        // Add the search query filter
        if ($query->searchQuery->hasValue()) {
            if ($search = $query->searchQuery->value) {
                $queryBuilder = $queryBuilder->where(function (
                    Builder $searchQueryBuilder,
                ) use ($search): void {
                    $searchQueryBuilder
                        ->where(
                            $this->_db->raw('LOWER(name)'),
                            'like',
                            '%' . mb_strtolower($search, 'UTF-8') . '%',
                        )
                        ->orWhere(
                            $this->_db->raw('LOWER(abbreviation)'),
                            'like',
                            '%' . mb_strtolower($search, 'UTF-8') . '%',
                        );
                });
            }
        }

        if ($query->notAssociatedToInstitutionIds->hasValue()) {
            $ids = $query->notAssociatedToInstitutionIds->value;
            if (!empty($ids)) {
                $queryBuilder = $queryBuilder->whereDoesntHave(
                    'institutions',
                    function (Builder $q) use ($ids): void {
                        $q->where((new Institution())->getKeyName(), $ids);
                    },
                );
            }
        }

        return $queryBuilder
            ->latest()
            ->paginate(perPage: $query->pageSize, page: $query->page);
    }
}
