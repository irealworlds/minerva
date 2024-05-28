<?php

declare(strict_types=1);

namespace App\ApplicationServices\Disciplines\Create;

use App\Core\Contracts\Cqrs\ICommand;
use App\Core\Models\Institution;

final readonly class DisciplineCreateCommand implements ICommand
{
    /**
     * @param iterable<Institution> $associatedInstitutions
     */
    public function __construct(
        public string $name,
        public string|null $abbreviation,
        public iterable $associatedInstitutions,
    ) {
    }
}
