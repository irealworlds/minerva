<?php

declare(strict_types=1);

namespace App\ApplicationServices\StudentRegistrations\Create;

use App\Core\{EmptyOptional, Optional};
use App\Core\Contracts\Cqrs\ICommand;
use App\Core\Dtos\PersonalNameDto;

final readonly class CreateStudentRegistrationCommand implements ICommand
{
    /**
     * @param Optional<string> $password
     */
    public function __construct(
        public string $studentKey,
        public string $username,
        public PersonalNameDto $name,
        public string $email,
        public Optional $password = new EmptyOptional(),
    ) {
    }
}
