<?php

declare(strict_types=1);

namespace App\ApplicationServices\Disciplines\FindByRouteKey;

use App\Core\Contracts\Cqrs\IQuery;
use App\Core\Models\Discipline;

/**
 * @implements IQuery<Discipline|null>
 */
final readonly class FindDisciplineByRouteKeyQuery implements IQuery
{
    public function __construct(public mixed $routeKey)
    {
    }
}
