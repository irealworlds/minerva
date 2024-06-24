<?php

declare(strict_types=1);

namespace App\ApplicationServices\StudentDisciplineEnrolments\ListFilteredPaginated;

use App\Core\Contracts\Cqrs\IQuery;
use App\Core\Models\StudentDisciplineEnrolment;
use App\Core\Optional;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\AbstractPaginator;

/**
 * @implements IQuery<LengthAwarePaginator<StudentDisciplineEnrolment>&AbstractPaginator<StudentDisciplineEnrolment>>
 */
final readonly class ListStudentDisciplineEnrolmentsFilteredPaginatedQuery
    implements IQuery
{
    /**
     * @param Optional<mixed> $disciplineKey
     * @param Optional<mixed> $educatorKey
     * @param Optional<mixed> $studentGroupKey
     * @param Optional<mixed> $studentRegistrationKey
     */
    function __construct(
        public int $page,
        public int $pageSize,
        public Optional $disciplineKey,
        public Optional $educatorKey,
        public Optional $studentGroupKey,
        public Optional $studentRegistrationKey,
    ) {
    }
}
