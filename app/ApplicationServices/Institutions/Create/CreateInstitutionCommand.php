<?php

declare(strict_types=1);

namespace App\ApplicationServices\Institutions\Create;

use App\Core\Contracts\Cqrs\ICommand;

final readonly class CreateInstitutionCommand implements ICommand
{
    public function __construct(
        public string $id,
        public string $name,
        public string|null $website = null,
        public string|null $parentId = null,
    ) {
    }
}
