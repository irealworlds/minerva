<?php

declare(strict_types=1);

namespace App\ApplicationServices\InstitutionEducators\FindByKeys;

use App\Core\Contracts\Cqrs\IQuery;
use App\Core\Models\InstitutionEducator;

/**
 * @implements IQuery<InstitutionEducator|null>
 */
final readonly class FindInstitutionEducatorByKeysQuery implements IQuery
{
    public function __construct(
        public mixed $institutionKey,
        public mixed $educatorKey,
    ) {
    }
}
