<?php

declare(strict_types=1);

namespace App\ApplicationServices\StudentDisciplineEnrolments\ListGroupedByDisciplineFilteredPaginated;

use App\Core\{EmptyOptional, Optional};
use App\Core\Contracts\Cqrs\IQuery;
use App\Core\Models\StudentDisciplineEnrolment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Enumerable;

/**
 * @implements IQuery<LengthAwarePaginator<array{
 *      0: string,
 *      1: Enumerable<int, StudentDisciplineEnrolment>
 *  }>&AbstractPaginator<array{
 *      0: string,
 *      1: Enumerable<int, StudentDisciplineEnrolment>
 *  }>>
 */
final readonly class ListStudentDisciplineEnrolmentsGroupedByDisciplineFilteredPaginatedQuery implements IQuery
{
    /**
     * @param Optional<mixed> $studentGroupEnrolmentKey
     */
    public function __construct(
        public int $page,
        public int $pageSize,
        public Optional $studentGroupEnrolmentKey = new EmptyOptional(),
    ) {
    }
}
