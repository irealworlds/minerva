<?php

declare(strict_types=1);

namespace App\ApplicationServices\StudentDisciplineGrades\ListFilteredPaginated;

use App\Core\Contracts\Cqrs\IQuery;
use App\Core\Models\StudentDisciplineGrade;
use App\Core\Optional;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\AbstractPaginator;

/**
 * @implements IQuery<LengthAwarePaginator<StudentDisciplineGrade>&AbstractPaginator<StudentDisciplineGrade>>
 */
final readonly class ListStudentDisciplineGradesFilteredPaginatedQuery implements IQuery
{
    /**
     * @param Optional<iterable<mixed>> $studentRegistrationKeys
     * @param Optional<iterable<mixed>> $disciplineKeys
     * @param Optional<iterable<mixed>> $studentGroupKeys
     */
    public function __construct(
        public int $page,
        public int $pageSize,
        public Optional $studentRegistrationKeys,
        public Optional $disciplineKeys,
        public Optional $studentGroupKeys,
    ) {
    }
}
