<?php

declare(strict_types=1);

namespace App\ApplicationServices\Institutions\FindById;

use App\Core\Contracts\Cqrs\IQueryHandler;
use App\Core\Models\Institution;
use Illuminate\Support\Enumerable;

/**
 * @implements IQueryHandler<FindInstitutionsByRouteKeysQuery, Enumerable<int, Institution>>
 */
final readonly class FindInstitutionsByRouteKeysHandler implements IQueryHandler
{
    /**
     * @inheritDoc
     */
    public function __invoke(mixed $query): Enumerable
    {
        return Institution::query()
            ->whereIn((new Institution())->getRouteKeyName(), $query->routeKeys)
            ->get();
    }
}
