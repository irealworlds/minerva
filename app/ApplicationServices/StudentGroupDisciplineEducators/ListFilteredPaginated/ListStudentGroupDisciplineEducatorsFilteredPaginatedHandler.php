<?php

declare(strict_types=1);

namespace App\ApplicationServices\StudentGroupDisciplineEducators\ListFilteredPaginated;

use App\Core\Contracts\Cqrs\IQueryHandler;
use App\Core\Models\StudentGroupDisciplineEducator;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\ConnectionResolverInterface;
use Illuminate\Pagination\AbstractPaginator;
use InvalidArgumentException;

/**
 * @implements IQueryHandler<ListStudentGroupDisciplineEducatorsFilteredPaginatedQuery, LengthAwarePaginator<StudentGroupDisciplineEducator>&AbstractPaginator<StudentGroupDisciplineEducator>>
 */
final readonly class ListStudentGroupDisciplineEducatorsFilteredPaginatedHandler
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
    ): LengthAwarePaginator&AbstractPaginator {
        $queryBuilder = StudentGroupDisciplineEducator::query();

        // Add the student group filter
        if ($query->studentGroupKey->hasValue()) {
            $queryBuilder = $queryBuilder->where(
                (new StudentGroupDisciplineEducator())
                    ->studentGroup()
                    ->getForeignKeyName(),
                $query->studentGroupKey->value,
            );
        }
        // Add the discipline filter
        if ($query->disciplineKey->hasValue()) {
            $queryBuilder = $queryBuilder->where(
                (new StudentGroupDisciplineEducator())
                    ->discipline()
                    ->getForeignKeyName(),
                $query->disciplineKey->value,
            );
        }
        // Add the educator filter
        if ($query->educatorKey->hasValue()) {
            $queryBuilder = $queryBuilder->where(
                (new StudentGroupDisciplineEducator())
                    ->educator()
                    ->getForeignKeyName(),
                $query->educatorKey->value,
            );
        }

        // Add the search query filter
        if ($query->searchQuery->hasValue()) {
            if ($search = $query->searchQuery->value) {
                $queryBuilder = $queryBuilder->whereHas(
                    (new StudentGroupDisciplineEducator())
                        ->discipline()
                        ->getRelationName(),
                    function (Builder $disciplineQueryBuilder) use ($search) {
                        return $disciplineQueryBuilder->where(function (
                            Builder $searchQueryBuilder,
                        ) use ($search): void {
                            $searchQueryBuilder
                                ->where(
                                    $this->_db
                                        ->connection()
                                        ->raw('LOWER(name)'),
                                    'like',
                                    '%' . mb_strtolower($search, 'UTF-8') . '%',
                                )
                                ->orWhere(
                                    $this->_db
                                        ->connection()
                                        ->raw('LOWER(abbreviation)'),
                                    'like',
                                    '%' . mb_strtolower($search, 'UTF-8') . '%',
                                );
                        });
                    },
                );
            }
        }

        return $queryBuilder->paginate(
            perPage: $query->pageSize,
            page: $query->page,
        );
    }
}
