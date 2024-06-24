<?php

declare(strict_types=1);

namespace App\ApplicationServices\StudentDisciplineEnrolments\ListByStudent;

use App\Core\Contracts\Cqrs\IQuery;
use App\Core\Models\StudentDisciplineEnrolment;
use App\Core\Optional;
use Illuminate\Support\Enumerable;

/**
 * @implements IQuery<Enumerable<int, StudentDisciplineEnrolment>>
 */
final readonly class ListStudentDisciplineEnrolmentsQuery implements IQuery
{
    /**
     * @param Optional<mixed> $disciplineKey
     */
    function __construct(
        public mixed $studentRegistrationKey,
        public Optional $disciplineKey,
    ) {
    }
}
