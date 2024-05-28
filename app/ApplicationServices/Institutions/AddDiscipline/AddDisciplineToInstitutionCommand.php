<?php

declare(strict_types=1);

namespace App\ApplicationServices\Institutions\AddDiscipline;

use App\Core\Contracts\Cqrs\ICommand;
use App\Core\Models\{Discipline, Institution};

final readonly class AddDisciplineToInstitutionCommand implements ICommand
{
    public function __construct(
        public Institution $institution,
        public Discipline $discipline,
    ) {
    }
}
