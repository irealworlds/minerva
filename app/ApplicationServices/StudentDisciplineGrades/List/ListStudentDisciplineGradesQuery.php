<?php

declare(strict_types=1);

namespace App\ApplicationServices\StudentDisciplineGrades\List;

use App\Core\Contracts\Cqrs\IQuery;
use App\Core\Models\StudentDisciplineGrade;
use App\Core\Optional;
use Illuminate\Support\Enumerable;

/**
 * @implements IQuery<Enumerable<int, StudentDisciplineGrade>>
 */
final readonly class ListStudentDisciplineGradesQuery implements IQuery
{
    /**
     * @param Optional<iterable<mixed>> $studentRegistrationKeys
     * @param Optional<iterable<mixed>> $disciplineKeys
     * @param Optional<iterable<mixed>> $studentGroupKeys
     */
    function __construct(
        public Optional $studentRegistrationKeys,
        public Optional $disciplineKeys,
        public Optional $studentGroupKeys,
    ) {
    }
}
