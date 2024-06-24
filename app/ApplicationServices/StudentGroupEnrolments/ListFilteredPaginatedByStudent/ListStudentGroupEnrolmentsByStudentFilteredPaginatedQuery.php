<?php

declare(strict_types=1);

namespace App\ApplicationServices\StudentGroupEnrolments\ListFilteredPaginatedByStudent;

use App\Core\Contracts\Cqrs\IQuery;
use App\Core\Models\StudentGroupEnrolment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\AbstractPaginator;

/**
 * @implements IQuery<LengthAwarePaginator<StudentGroupEnrolment>&AbstractPaginator<StudentGroupEnrolment>>
 */
final readonly class ListStudentGroupEnrolmentsByStudentFilteredPaginatedQuery
    implements IQuery
{
    function __construct(
        public int $page,
        public int $pageSize,
        public mixed $studentRegistrationKey,
    ) {
    }
}
