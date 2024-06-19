<?php

declare(strict_types=1);

namespace App\ApplicationServices\StudentRegistrations\ListFilteredPaginated;

use App\Core\Contracts\Cqrs\IQuery;
use App\Core\Models\StudentRegistration;
use App\Core\Optional;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\AbstractPaginator;

/**
 * @implements IQuery<LengthAwarePaginator<StudentRegistration> & AbstractPaginator<StudentRegistration>>
 */
final readonly class ListFilteredPaginatedStudentRegistrationsQuery implements
    IQuery
{
    /**
     * @param Optional<string> $searchQuery
     */
    public function __construct(
        public int $page,
        public int $pageSize,
        public Optional $searchQuery,
    ) {
    }
}
