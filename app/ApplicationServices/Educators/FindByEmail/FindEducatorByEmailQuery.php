<?php

declare(strict_types=1);

namespace App\ApplicationServices\Educators\FindByEmail;

use App\Core\Contracts\Cqrs\IQuery;
use App\Core\Models\Educator;

/**
 * @implements IQuery<Educator|null>
 */
final readonly class FindEducatorByEmailQuery implements IQuery
{
    public function __construct(public string $email)
    {
    }
}
