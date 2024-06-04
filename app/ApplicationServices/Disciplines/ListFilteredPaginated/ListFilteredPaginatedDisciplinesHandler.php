<?php

declare(strict_types=1);

namespace App\ApplicationServices\Disciplines\ListFilteredPaginated;

use App\Core\Contracts\Cqrs\IQueryHandler;
use App\Core\Models\{Discipline, Institution, StudentGroup};
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
     *
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

        // Add the associated to institution filter
        if ($query->associatedToInstitutionIds->hasValue()) {
            $ids = $query->associatedToInstitutionIds->value;
            if (!empty($ids)) {
                $queryBuilder = $queryBuilder->whereHas(
                    'institutions',
                    function (Builder $q) use ($ids): void {
                        $q->whereIn((new Institution())->getKeyName(), $ids);
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
                        $q->whereIn((new Institution())->getKeyName(), $ids);
                    },
                );
            }
        }

        // Add the associated to student group filter
        if ($query->associatedToStudentGroupIds->hasValue()) {
            $ids = $query->associatedToStudentGroupIds->value;
            if (!empty($ids)) {
                $queryBuilder = $queryBuilder->whereHas(
                    'studentGroups',
                    function (Builder $q) use ($ids): void {
                        $q->whereIn((new StudentGroup())->getKeyName(), $ids);
                    },
                );
            }
        }

        // Add the not associated to student group filter
        if ($query->notAssociatedToStudentGroupIds->hasValue()) {
            $ids = $query->notAssociatedToStudentGroupIds->value;
            if (!empty($ids)) {
                $queryBuilder = $queryBuilder->whereDoesntHave(
                    'studentGroups',
                    function (Builder $q) use ($ids): void {
                        $q->whereIn((new StudentGroup())->getKeyName(), $ids);
                    },
                );
            }
        }

        return $queryBuilder
            ->latest()
            ->paginate(perPage: $query->pageSize, page: $query->page);
    }
}
