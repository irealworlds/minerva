<?php

declare(strict_types=1);

namespace App\ApplicationServices\Educators\FindByRouteKey;

use App\Core\Contracts\Cqrs\IQueryHandler;
use App\Core\Models\Educator;

/**
 * @implements IQueryHandler<FindEducatorByRouteKeyQuery, Educator|null>
 */
final readonly class FindEducatorByRouteKeyHandler implements IQueryHandler
{
    /**
     * @inheritDoc
     */
    public function __invoke(mixed $query): Educator|null
    {
        return Educator::query()
            ->where((new Educator())->getRouteKeyName(), $query->routeKey)
            ->first();
    }
}
