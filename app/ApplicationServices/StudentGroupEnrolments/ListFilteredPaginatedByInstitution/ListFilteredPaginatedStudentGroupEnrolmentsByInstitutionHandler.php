<?php

declare(strict_types=1);

namespace App\ApplicationServices\StudentGroupEnrolments\ListFilteredPaginatedByInstitution;

use App\Core\Contracts\Cqrs\IQueryHandler;
use App\Core\Models\{Institution, StudentGroup, StudentGroupEnrolment};
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\{Builder, Model};
use Illuminate\Pagination\AbstractPaginator;
use InvalidArgumentException;

/**
 * @implements IQueryHandler<ListFilteredPaginatedStudentGroupEnrolmentsByInstitutionQuery, LengthAwarePaginator<StudentGroupEnrolment> & AbstractPaginator<StudentGroupEnrolment>>
 */
final readonly class ListFilteredPaginatedStudentGroupEnrolmentsByInstitutionHandler implements IQueryHandler
{
    /**
     * @inheritDoc
     * @throws InvalidArgumentException
     */
    public function __invoke(
        mixed $query,
    ): AbstractPaginator&LengthAwarePaginator {
        $queryBuilder = StudentGroupEnrolment::query();

        // Add the institution key filter
        $queryBuilder = $this->addParentInstitutionKeyConstraint(
            $queryBuilder,
            $query->institution,
        );

        return $queryBuilder
            ->latest()
            ->paginate(perPage: $query->pageSize, page: $query->page);
    }

    /**
     * @param Builder<StudentGroupEnrolment> $queryBuilder
     * @return Builder<StudentGroupEnrolment>
     * @todo Maybe denormalize the institution keys to the student group enrolments table
     */
    private function addParentInstitutionKeyConstraint(
        Builder $queryBuilder,
        Institution $institution,
    ): Builder {
        // Get a list of all institution keys that are descendants of the given institution
        $institutionKeyColumn = (new Institution())->getKeyName();
        $allowedInstitutionKeys = $institution
            ->descendantsAndSelf()
            ->get([$institutionKeyColumn])
            ->pluck($institutionKeyColumn)
            ->toArray();

        // Get a list of all student group keys that are descendants of the given institution
        $allowedStudentGroupKeys = [];
        $this->fetchDescendantStudentGroupIds(
            $allowedInstitutionKeys,
            Institution::class,
            $allowedStudentGroupKeys,
        );

        // Add the constraint to the query builder
        return $queryBuilder->whereIn(
            (new StudentGroupEnrolment())->studentGroup()->getForeignKeyName(),
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
