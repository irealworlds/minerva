<?php

declare(strict_types=1);

namespace App\ApplicationServices\StudentGroups\ListByEducatorFilteredPaginated;

use App\Core\Contracts\Cqrs\IQuery;
use App\Core\Models\StudentGroup;
use App\Core\Optional;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\AbstractPaginator;

/**
 * @implements IQuery<LengthAwarePaginator<StudentGroup> & AbstractPaginator<StudentGroup>>
 */
final readonly class ListStudentGroupsByEducatorFilteredPaginatedQuery
    implements IQuery
{
    /**
     * @param Optional<string> $searchQuery
     */
    function __construct(
        public int $page,
        public int $pageSize,
        public mixed $educatorKey,
        public Optional $searchQuery,
    ) {
    }
}
