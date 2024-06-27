<?php

declare(strict_types=1);

namespace App\ApplicationServices\Identities\UpdatePassword;

use App\Core\Contracts\Cqrs\ICommand;
use App\Core\Models\Identity;

final readonly class UpdateIdentityPasswordCommand implements ICommand
{
    public function __construct(
        public Identity $identity,
        public string $password,
    ) {
    }
}
