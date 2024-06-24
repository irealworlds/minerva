<?php

declare(strict_types=1);

namespace App\ApplicationServices\StudentGroupDisciplineEducators\ListFilteredPaginated;

use App\Core\Contracts\Cqrs\IQuery;
use App\Core\Models\StudentGroupDisciplineEducator;
use App\Core\Optional;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\AbstractPaginator;

/**
 * @implements IQuery<LengthAwarePaginator<StudentGroupDisciplineEducator>&AbstractPaginator<StudentGroupDisciplineEducator>>
 */
final readonly class ListStudentGroupDisciplineEducatorsFilteredPaginatedQuery
    implements IQuery
{
    /**
     * @param Optional<mixed> $studentGroupKey
     * @param Optional<mixed> $disciplineKey
     * @param Optional<mixed> $educatorKey
     * @param Optional<string> $searchQuery
     */
    function __construct(
        public int $page,
        public int $pageSize,
        public Optional $studentGroupKey,
        public Optional $disciplineKey,
        public Optional $educatorKey,
        public Optional $searchQuery,
    ) {
    }
}
