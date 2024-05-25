<?php

declare(strict_types=1);

namespace App\ApplicationServices\Identities\Update;

use App\Core\Contracts\Cqrs\ICommand;
use App\Core\Models\Identity;

final readonly class UpdateIdentityCommand implements ICommand
{
    public function __construct(
        public Identity $identity,
        public string $email
    ) {
    }
}
