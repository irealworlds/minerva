<?php

declare(strict_types=1);

namespace App\ApplicationServices\Educators\AssociateToInstitution;

use App\Core\Contracts\Cqrs\ICommand;
use App\Core\Models\{Educator, Institution};

final readonly class AssociateEducatorToInstitutionCommand implements ICommand
{
    /**
     * @param iterable<string> $roles
     */
    public function __construct(
        public Educator $educator,
        public Institution $institution,
        public iterable $roles,
    ) {
    }
}
