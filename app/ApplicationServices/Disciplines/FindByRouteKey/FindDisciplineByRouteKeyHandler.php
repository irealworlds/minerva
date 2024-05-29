<?php

declare(strict_types=1);

namespace App\ApplicationServices\Disciplines\FindByRouteKey;

use App\Core\Contracts\Cqrs\IQueryHandler;
use App\Core\Models\Discipline;

/**
 * @implements IQueryHandler<FindDisciplineByRouteKeyQuery, Discipline|null>
 */
final readonly class FindDisciplineByRouteKeyHandler implements IQueryHandler
{
    /**
     * @inheritDoc
     */
    public function __invoke(mixed $query): Discipline|null
    {
        return Discipline::query()
            ->where((new Discipline())->getRouteKeyName(), $query->routeKey)
            ->first();
    }
}
