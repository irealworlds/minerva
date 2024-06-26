<?php

declare(strict_types=1);

namespace App\ApplicationServices\Identities\FindByUsername;

use App\Core\Contracts\Cqrs\IQuery;
use App\Core\Models\Identity;

/**
 * @implements IQuery<Identity|null>
 */
final readonly class FindIdentityByUsernameQuery implements IQuery
{
    public function __construct(public string $username)
    {
    }
}
