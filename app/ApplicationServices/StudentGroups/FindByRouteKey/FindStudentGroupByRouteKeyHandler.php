<?php

declare(strict_types=1);

namespace App\ApplicationServices\StudentGroups\FindByRouteKey;

use App\Core\Contracts\Cqrs\IQueryHandler;
use App\Core\Models\StudentGroup;

/**
 * @implements IQueryHandler<FindStudentGroupByRouteKeyQuery, StudentGroup|null>
 */
final readonly class FindStudentGroupByRouteKeyHandler implements IQueryHandler
{
    /**
     * @inheritDoc
     */
    public function __invoke(mixed $query): StudentGroup|null
    {
        return StudentGroup::query()
            ->where((new StudentGroup())->getRouteKeyName(), $query->routeKey)
            ->first();
    }
}
