<?php

declare(strict_types=1);

namespace App\ApplicationServices\StudentGroups\ListByEducatorFilteredPaginated;

use App\Core\Contracts\Cqrs\IQueryHandler;
use App\Core\Models\StudentGroup;
use App\Core\Models\StudentGroupDisciplineEducator;
use App\Core\Optional;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\ConnectionResolverInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\AbstractPaginator;
use InvalidArgumentException;

/**
 * @implements IQueryHandler<ListStudentGroupsByEducatorFilteredPaginatedQuery, LengthAwarePaginator<StudentGroup> & AbstractPaginator<StudentGroup>>
 */
final readonly class ListStudentGroupsByEducatorFilteredPaginatedHandler
    implements IQueryHandler
{
    function __construct(private ConnectionResolverInterface $_db)
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

        // Add the educator filter
        $queryBuilder = $this->addEducatorFilter(
            $queryBuilder,
            $query->educatorKey,
        );

        // Add the search query filter
        $queryBuilder = $this->addSearchQueryFilter(
            $queryBuilder,
            $query->searchQuery,
        );

        return $queryBuilder
            ->latest()
            ->paginate(perPage: $query->pageSize, page: $query->page);
    }

    /**
     * @param Builder<StudentGroup> $queryBuilder
     * @return Builder<StudentGroup>
     */
    public function addEducatorFilter(
        Builder $queryBuilder,
        mixed $educatorKey,
    ): Builder {
        return $queryBuilder->whereHas('disciplineEducators', function (
            Builder $disciplineEducatorQuery,
        ) use ($educatorKey) {
            $disciplineEducatorQuery->where(
                (new StudentGroupDisciplineEducator())
                    ->educator()
                    ->getForeignKeyName(),
                $educatorKey,
            );
        });
    }

    /**
     * @param Optional<string> $searchQuery
     * @param Builder<StudentGroup> $queryBuilder
     * @return Builder<StudentGroup>
     */
    public function addSearchQueryFilter(
        Builder $queryBuilder,
        Optional $searchQuery,
    ): Builder {
        if ($searchQuery->hasValue()) {
            if ($search = $searchQuery->value) {
                $queryBuilder = $queryBuilder->where(function (
                    Builder $searchQueryBuilder,
                ) use ($search): void {
                    $searchQueryBuilder->where(
                        $this->_db->connection()->raw('LOWER(name)'),
                        'like',
                        '%' . mb_strtolower($search, 'UTF-8') . '%',
                    );
                });
            }
        }

        return $queryBuilder;
    }
}
