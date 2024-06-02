<?php

declare(strict_types=1);

namespace App\ApplicationServices\InstitutionEducators\FindByKeys;

use App\Core\Contracts\Cqrs\IQueryHandler;
use App\Core\Models\InstitutionEducator;

/**
 * @implements IQueryHandler<FindInstitutionEducatorByKeysQuery, InstitutionEducator|null>
 */
final readonly class FindInstitutionEducatorByKeysHandler implements
    IQueryHandler
{
    /**
     * @inheritDoc
     */
    public function __invoke(mixed $query): InstitutionEducator|null
    {
        return InstitutionEducator::query()
            ->where(
                (new InstitutionEducator())->institution()->getForeignKeyName(),
                $query->institutionKey,
            )
            ->where(
                (new InstitutionEducator())->educator()->getForeignKeyName(),
                $query->educatorKey,
            )
            ->first();
    }
}
