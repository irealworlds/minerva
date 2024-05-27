<?php

declare(strict_types=1);

namespace App\ApplicationServices\StudentGroups\ListFilteredPaginated;

use App\Core\Contracts\Cqrs\IQuery;
use App\Core\Models\StudentGroup;
use App\Core\Optional;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\AbstractPaginator;

/**
 * @implements IQuery<LengthAwarePaginator<StudentGroup> & AbstractPaginator<StudentGroup>>
 */
final readonly class ListFilteredPaginatedStudentGroupsQuery implements IQuery
{
    /**
     * @param Optional<string|null> $parentType
     * @param Optional<string|null> $parentId
     * @param Optional<string> $searchQuery
     */
    public function __construct(
        public int $page,
        public int $pageSize,
        public Optional $parentType,
        public Optional $parentId,
        public Optional $searchQuery
    ) {
    }
}
