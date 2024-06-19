<?php

declare(strict_types=1);

namespace App\ApplicationServices\StudentGroups\FindByRouteKey;

use App\Core\Contracts\Cqrs\IQuery;
use App\Core\Models\StudentGroup;

/**
 * @implements IQuery<StudentGroup|null>
 */
final readonly class FindStudentGroupByRouteKeyQuery implements IQuery
{
    public function __construct(public string $routeKey)
    {
    }
}
