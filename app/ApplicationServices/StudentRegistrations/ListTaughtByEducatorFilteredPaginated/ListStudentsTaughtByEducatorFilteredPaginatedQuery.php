<?php

declare(strict_types=1);

namespace App\ApplicationServices\StudentRegistrations\ListTaughtByEducatorFilteredPaginated;

use App\Core\Contracts\Cqrs\IQuery;
use App\Core\Models\StudentRegistration;
use App\Core\Optional;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\AbstractPaginator;

/**
 * @implements IQuery<LengthAwarePaginator<StudentRegistration> & AbstractPaginator<StudentRegistration>>
 */
final readonly class ListStudentsTaughtByEducatorFilteredPaginatedQuery
    implements IQuery
{
    /**
     * @param Optional<mixed> $studentGroupKey
     * @param Optional<mixed> $disciplineKey
     * @param Optional<string> $searchQuery
     */
    public function __construct(
        public int $page,
        public int $pageSize,
        public mixed $educatorKey,
        public Optional $studentGroupKey,
        public Optional $disciplineKey,
        public Optional $searchQuery,
    ) {
    }
}
