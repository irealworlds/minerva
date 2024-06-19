<?php

declare(strict_types=1);

namespace App\ApplicationServices\StudentGroupEnrolments\ListFilteredPaginatedByInstitution;

use App\Core\Contracts\Cqrs\IQuery;
use App\Core\Models\{Institution, StudentGroupEnrolment};
use App\Core\Optional;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\AbstractPaginator;

/**
 * @implements IQuery<LengthAwarePaginator<StudentGroupEnrolment> & AbstractPaginator<StudentGroupEnrolment>>
 */
final readonly class ListFilteredPaginatedStudentGroupEnrolmentsByInstitutionQuery implements IQuery
{
    /**
     * @param Optional<string> $searchQuery
     */
    public function __construct(
        public Institution $institution,
        public int $page,
        public int $pageSize,
        public Optional $searchQuery,
    ) {
    }
}
