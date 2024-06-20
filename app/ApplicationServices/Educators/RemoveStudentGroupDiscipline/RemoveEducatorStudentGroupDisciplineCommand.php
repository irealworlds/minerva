<?php

declare(strict_types=1);

namespace App\ApplicationServices\Educators\RemoveStudentGroupDiscipline;

use App\Core\Contracts\Cqrs\ICommand;

final readonly class RemoveEducatorStudentGroupDisciplineCommand implements
    ICommand
{
    public function __construct(
        public mixed $educatorKey,
        public mixed $studentGroupKey,
        public mixed $disciplineKey,
    ) {
    }
}
