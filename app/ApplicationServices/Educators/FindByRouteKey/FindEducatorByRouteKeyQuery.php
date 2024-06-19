<?php

declare(strict_types=1);

namespace App\ApplicationServices\Educators\FindByRouteKey;

use App\Core\Contracts\Cqrs\IQuery;
use App\Core\Models\Educator;

/**
 * @implements IQuery<Educator|null>
 */
final readonly class FindEducatorByRouteKeyQuery implements IQuery
{
    public function __construct(public string $routeKey)
    {
    }
}
