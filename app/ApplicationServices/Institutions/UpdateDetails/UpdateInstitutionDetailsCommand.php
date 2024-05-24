<?php

namespace App\ApplicationServices\Institutions\UpdateDetails;

use App\Core\Contracts\Cqrs\ICommand;
use App\Core\Models\Institution;
use App\Core\Optional;

final readonly class UpdateInstitutionDetailsCommand implements ICommand
{
    /**
     * @param Institution $institution
     * @param Optional<string> $name
     * @param Optional<string|null> $website
     */
    function __construct(
        public Institution $institution,
        public Optional $name,
        public Optional $website
    ) {
    }
}
