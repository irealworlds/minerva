<?php

declare(strict_types=1);

namespace App\ApplicationServices\StudentDisciplineEnrolments\FindByRouteKey;

use App\Core\Contracts\Cqrs\IQuery;
use App\Core\Models\StudentDisciplineEnrolment;

/**
 * @implements IQuery<StudentDisciplineEnrolment|null>
 */
final readonly class FindStudentDisciplineEnrolmentByRouteKeyQuery implements
    IQuery
{
    function __construct(public mixed $routeKey)
    {
    }
}
