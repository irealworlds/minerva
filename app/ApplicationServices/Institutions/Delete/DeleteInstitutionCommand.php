<?php

namespace App\ApplicationServices\Institutions\Delete;

use App\Core\Contracts\Cqrs\ICommand;
use App\Core\Models\Institution;

final readonly class DeleteInstitutionCommand implements ICommand
{
    function __construct(
        public Institution $institution
    ) {
    }
}
