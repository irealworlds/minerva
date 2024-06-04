<?php

declare(strict_types=1);

namespace App\ApplicationServices\Identities\Update;

use App\Core\Contracts\Cqrs\ICommand;
use App\Core\Models\Identity;
use App\Core\Optional;

final readonly class UpdateIdentityCommand implements ICommand
{
    /**
     * @param Optional<string|null> $namePrefix
     * @param Optional<string> $firstName
     * @param Optional<string[]> $middleNames
     * @param Optional<string> $lastName
     * @param Optional<string|null> $nameSuffix
     * @param Optional<string> $email
     */
    public function __construct(
        public Identity $identity,
        public Optional $namePrefix,
        public Optional $firstName,
        public Optional $middleNames,
        public Optional $lastName,
        public Optional $nameSuffix,
        public Optional $email,
    ) {
    }
}
