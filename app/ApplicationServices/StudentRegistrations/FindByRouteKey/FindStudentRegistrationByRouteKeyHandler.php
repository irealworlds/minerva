<?php

declare(strict_types=1);

namespace App\ApplicationServices\StudentRegistrations\FindByRouteKey;

use App\Core\Contracts\Cqrs\IQueryHandler;
use App\Core\Models\StudentRegistration;

/**
 * @implements IQueryHandler<FindStudentRegistrationByRouteKeyQuery, StudentRegistration|null>
 */
final readonly class FindStudentRegistrationByRouteKeyHandler implements
    IQueryHandler
{
    /**
     * @inheritDoc
     */
    public function __invoke(mixed $query): StudentRegistration|null
    {
        return StudentRegistration::query()
            ->where(
                (new StudentRegistration())->getRouteKeyName(),
                $query->routeKey,
            )
            ->first();
    }
}
