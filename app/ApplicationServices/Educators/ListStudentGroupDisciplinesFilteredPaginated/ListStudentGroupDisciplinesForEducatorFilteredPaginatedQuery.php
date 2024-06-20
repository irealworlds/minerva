<?php

declare(strict_types=1);

namespace App\ApplicationServices\Educators\ListStudentGroupDisciplinesFilteredPaginated;

use App\Core\Contracts\Cqrs\IQuery;
use App\Core\Models\StudentGroupDisciplineEducator;
use App\Core\Optional;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\AbstractPaginator;

/**
 * @implements IQuery<LengthAwarePaginator&AbstractPaginator<StudentGroupDisciplineEducator>>
 */
final readonly class ListStudentGroupDisciplinesForEducatorFilteredPaginatedQuery implements IQuery
{
    /**
     * @param Optional<mixed> $institutionKey
     */
    public function __construct(
        public mixed $educatorKey,
        public int $page,
        public int $pageSize,
        public Optional $institutionKey,
    ) {
    }
}
