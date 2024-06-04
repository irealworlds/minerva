<?php

declare(strict_types=1);

namespace App\ApplicationServices\Institutions\ListFilteredPaginatedEducators;

use App\Core\Contracts\Cqrs\IQueryHandler;
use App\Core\Models\{Educator, InstitutionEducator};
use Illuminate\Contracts\Database\Eloquent\Builder as EloquentQueryBuilder;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\ConnectionResolverInterface;
use Illuminate\Pagination\AbstractPaginator;
use InvalidArgumentException;

/**
 * @implements IQueryHandler<ListFilteredPaginatedInstitutionEducatorsQuery, LengthAwarePaginator<InstitutionEducator> & AbstractPaginator<InstitutionEducator>>
 */
final readonly class ListFilteredPaginatedInstitutionEducatorsHandler implements
    IQueryHandler
{
    public function __construct(private ConnectionResolverInterface $_db)
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
        $queryBuilder = InstitutionEducator::query();

        // Apply filter for institution
        $queryBuilder = $queryBuilder->where(
            (new InstitutionEducator())->institution()->getForeignKeyName(),
            $query->institutionId,
        );

        // Add the search query filter
        if ($query->searchQuery->hasValue()) {
            if ($search = $query->searchQuery->value) {
                $queryBuilder = $queryBuilder->whereHas(
                    (new InstitutionEducator())->educator()->getRelationName(),
                    function (EloquentQueryBuilder $educatorQueryBuilder) use (
                        $search,
                    ): void {
                        $educatorQueryBuilder->whereHas(
                            (new Educator())->identity()->getRelationName(),
                            function (Builder $searchQueryBuilder) use (
                                $search,
                            ): void {
                                $searchQueryBuilder->where(
                                    $this->_db
                                        ->connection()
                                        ->raw('LOWER(email)'),
                                    'like',
                                    '%' . mb_strtolower($search, 'UTF-8') . '%',
                                );
                            },
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
