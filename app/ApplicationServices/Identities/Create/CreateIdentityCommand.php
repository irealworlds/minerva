<?php

declare(strict_types=1);

namespace App\ApplicationServices\Identities\Create;

use App\Core\{EmptyOptional, Optional};
use App\Core\Contracts\Cqrs\ICommand;
use App\Core\Dtos\PersonalNameDto;

final readonly class CreateIdentityCommand implements ICommand
{
    /**
     * @param Optional<string> $password
     */
    public function __construct(
        public string $username,
        public PersonalNameDto $name,
        public string $email,
        public Optional $password = new EmptyOptional(),
    ) {
    }
}
