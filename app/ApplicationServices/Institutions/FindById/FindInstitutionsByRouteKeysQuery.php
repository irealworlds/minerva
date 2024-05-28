<?php

declare(strict_types=1);

namespace App\ApplicationServices\Institutions\FindById;

use App\Core\Contracts\Cqrs\IQuery;
use App\Core\Models\Institution;
use Illuminate\Support\Enumerable;

/**
 * @implements IQuery<Enumerable<int, Institution>>
 */
final readonly class FindInstitutionsByRouteKeysQuery implements IQuery
{
    /** @var iterable<string> $routeKeys */
    public iterable $routeKeys;

    public function __construct(string ...$routeKeys)
    {
        $this->routeKeys = $routeKeys;
    }
}
