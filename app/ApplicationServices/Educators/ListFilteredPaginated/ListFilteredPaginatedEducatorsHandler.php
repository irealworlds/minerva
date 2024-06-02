<?php

declare(strict_types=1);

namespace App\ApplicationServices\Educators\ListFilteredPaginated;

use App\Core\Contracts\Cqrs\IQueryHandler;
use App\Core\Models\{Educator, Institution};
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\ConnectionResolverInterface;
use Illuminate\Pagination\AbstractPaginator;
use InvalidArgumentException;

/**
 * @implements IQueryHandler<ListFilteredPaginatedEducatorsQuery, LengthAwarePaginator<Educator> & AbstractPaginator<Educator>>
 */
final readonly class ListFilteredPaginatedEducatorsHandler implements
    IQueryHandler
{
    public function __construct(private ConnectionResolverInterface $_db)
    {
    }

    /**
     * @inheritDoc
     * @throws InvalidArgumentException
     */
    public function __invoke(
        mixed $query,
    ): AbstractPaginator&LengthAwarePaginator {
        $queryBuilder = Educator::query();

        // Add the search query filter
        if ($query->searchQuery->hasValue()) {
            if ($search = $query->searchQuery->value) {
                $queryBuilder = $queryBuilder->whereHas('identity', function (
                    Builder $searchQueryBuilder,
                ) use ($search): void {
                    $searchQueryBuilder->where(
                        $this->_db->connection()->raw('LOWER(email)'),
                        'like',
                        '%' . mb_strtolower($search, 'UTF-8') . '%',
                    );
                });
            }
        }

        // Add the associated to institution filter
        if ($query->associatedToInstitutionIds->hasValue()) {
            $ids = $query->associatedToInstitutionIds->value;
            if (!empty($ids)) {
                $queryBuilder = $queryBuilder->whereHas(
                    'institutions',
                    function (Builder $q) use ($ids): void {
                        $q->whereIn(
                            (new Institution())->getQualifiedKeyName(),
                            $ids,
                        );
                    },
                );
            }
        }

        // Add the not associated to institution filter
        if ($query->notAssociatedToInstitutionIds->hasValue()) {
            $ids = $query->notAssociatedToInstitutionIds->value;
            if (!empty($ids)) {
                $queryBuilder = $queryBuilder->whereDoesntHave(
                    'institutions',
                    function (Builder $q) use ($ids): void {
                        $q->whereIn(
                            (new Institution())->getQualifiedKeyName(),
                            $ids,
                        );
                    },
                );
            }
        }

        return $queryBuilder
            ->latest()
            ->paginate(perPage: $query->pageSize, page: $query->page);
    }
}
