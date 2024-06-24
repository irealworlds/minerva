<?php

declare(strict_types=1);

namespace App\ApplicationServices\StudentDisciplineEnrolments\FindByRelatedEntities;

use App\Core\Contracts\Cqrs\IQuery;
use App\Core\Models\StudentDisciplineEnrolment;

/**
 * @implements IQuery<StudentDisciplineEnrolment|null>
 */
final readonly class FindStudentDisciplineEnrolmentByRelatedEntitiesQuery
    implements IQuery
{
    function __construct(
        public mixed $studentGroupKey,
        public mixed $studentRegistrationKey,
        public mixed $disciplineKey,
        public mixed $educatorKey,
    ) {
    }
}
