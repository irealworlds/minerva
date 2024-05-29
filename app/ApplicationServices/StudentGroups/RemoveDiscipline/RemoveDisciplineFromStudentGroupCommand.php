<?php

declare(strict_types=1);

namespace App\ApplicationServices\StudentGroups\RemoveDiscipline;

use App\Core\Contracts\Cqrs\ICommand;
use App\Core\Models\{Discipline, StudentGroup};

final readonly class RemoveDisciplineFromStudentGroupCommand implements ICommand
{
    public function __construct(
        public StudentGroup $group,
        public Discipline $discipline,
    ) {
    }
}
