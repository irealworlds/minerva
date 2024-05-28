<?php

declare(strict_types=1);

namespace App\ApplicationServices\Institutions\RemoveDiscipline;

use App\Core\Contracts\Cqrs\ICommand;
use App\Core\Models\{Discipline, Institution};

final readonly class RemoveDisciplineFromInstitutionCommand implements ICommand
{
    public function __construct(
        public Institution $institution,
        public Discipline $discipline,
    ) {
    }
}
