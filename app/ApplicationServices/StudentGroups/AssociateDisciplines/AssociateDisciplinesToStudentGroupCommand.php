<?php

declare(strict_types=1);

namespace App\ApplicationServices\StudentGroups\AssociateDisciplines;

use App\Core\Contracts\Cqrs\ICommand;
use App\Core\Models\{Discipline, StudentGroup};

final readonly class AssociateDisciplinesToStudentGroupCommand implements
    ICommand
{
    /**
     * @param iterable<Discipline> $disciplines
     */
    public function __construct(
        public StudentGroup $studentGroup,
        public iterable $disciplines,
    ) {
    }
}
