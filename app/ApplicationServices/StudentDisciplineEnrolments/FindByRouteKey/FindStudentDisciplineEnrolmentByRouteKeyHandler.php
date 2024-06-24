<?php

declare(strict_types=1);

namespace App\ApplicationServices\StudentDisciplineEnrolments\FindByRouteKey;

use App\Core\Contracts\Cqrs\IQueryHandler;
use App\Core\Models\StudentDisciplineEnrolment;

/**
 * @implements IQueryHandler<FindStudentDisciplineEnrolmentByRouteKeyQuery, StudentDisciplineEnrolment|null>
 */
final readonly class FindStudentDisciplineEnrolmentByRouteKeyHandler implements
    IQueryHandler
{
    /**
     * @inheritDoc
     */
    public function __invoke(mixed $query): StudentDisciplineEnrolment|null
    {
        return StudentDisciplineEnrolment::query()
            ->where(
                (new StudentDisciplineEnrolment())->getRouteKeyName(),
                $query->routeKey,
            )
            ->first();
    }
}
