<?php

namespace App\ApplicationServices\Institutions\Create;

use App\Core\Contracts\Cqrs\ICommand;

final readonly class CreateInstitutionCommand implements ICommand
{
    function __construct(
        public string $id,
        public string $name,
        public string|null $website = null,
        public string|null $parentId = null,
    ) {
    }
}
