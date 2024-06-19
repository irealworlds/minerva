<?php

declare(strict_types=1);

namespace App\ApplicationServices\StudentRegistrations\FindByRouteKey;

use App\Core\Contracts\Cqrs\IQuery;
use App\Core\Models\StudentRegistration;

/**
 * @implements IQuery<StudentRegistration|null>
 */
final readonly class FindStudentRegistrationByRouteKeyQuery implements IQuery
{
    public function __construct(public string $routeKey)
    {
    }
}
