<?php

declare(strict_types=1);

namespace App\ApplicationServices\Institutions\RemoveEducator;

use App\Core\Contracts\Cqrs\ICommand;
use App\Core\Models\{Educator, Institution};

final readonly class RemoveInstitutionEducatorCommand implements ICommand
{
    public function __construct(
        public Institution $institution,
        public Educator $educator,
    ) {
    }
}
