<?php

declare(strict_types=1);

namespace App\ApplicationServices\StudentGroups\ListFilteredPaginated;

use App\Core\Contracts\Cqrs\IQueryHandler;
use App\Core\Models\{Institution, StudentGroup};
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Eloquent\{Builder as EloquentBuilder, Model};
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
     *
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

        if ($query->descendantOfInstitutionIds->hasValue()) {
            $queryBuilder = $this->addParentInstitutionKeyConstraint(
                $queryBuilder,
                $query->descendantOfInstitutionIds->value,
            );
        }

        return $queryBuilder
            ->with(['parent', 'childGroups'])
            ->latest()
            ->paginate(perPage: $query->pageSize, page: $query->page);
    }

    /**
     * @param EloquentBuilder<StudentGroup> $queryBuilder
     * @param mixed[] $allowedInstitutionKeys
     * @return EloquentBuilder<StudentGroup>
     * @todo Maybe denormalize the institution keys to the student group enrolments table
     */
    private function addParentInstitutionKeyConstraint(
        Builder $queryBuilder,
        array $allowedInstitutionKeys,
    ): Builder {
        // Get a list of all student group keys that are descendants of the given institution
        $allowedStudentGroupKeys = [];
        $this->fetchDescendantStudentGroupIds(
            $allowedInstitutionKeys,
            Institution::class,
            $allowedStudentGroupKeys,
        );

        // Add the constraint to the query builder
        return $queryBuilder->whereIn(
            (new StudentGroup())->getKeyName(),
            $allowedStudentGroupKeys,
        );
    }

    /**
     * @param mixed[] $parentIds
     * @param class-string<Model> $parentType
     * @param mixed[] $descendantIds
     */
    private function fetchDescendantStudentGroupIds(
        array $parentIds,
        string $parentType,
        array &$descendantIds,
    ): void {
        // Get the child student groups of the current parent
        $childStudentGroupIds = StudentGroup::query()
            ->where(
                (new StudentGroup())->parent()->getMorphType(), // parent_type
                $parentType,
            )
            ->whereIn(
                (new StudentGroup())->parent()->getForeignKeyName(), // parent_id
                $parentIds,
            )
            ->pluck((new StudentGroup())->getKeyName())
            ->toArray();

        // Add child student groups to the descendant IDs
        $descendantIds = array_merge($descendantIds, $childStudentGroupIds);

        // Recursively fetch descendant IDs for each child student group
        if (!empty($childStudentGroupIds)) {
            $this->fetchDescendantStudentGroupIds(
                $childStudentGroupIds,
                StudentGroup::class,
                $descendantIds,
            );
        }
    }
}
