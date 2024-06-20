<?php

declare(strict_types=1);

namespace App\ApplicationServices\Educators\AddStudentGroupDisciplines;

use App\Core\Contracts\Cqrs\ICommand;

final readonly class AddStudentGroupDisciplinesToEducatorsCommand implements
    ICommand
{
    /**
     * @param iterable<mixed> $disciplineKeys
     */
    public function __construct(
        public mixed $educatorKey,
        public mixed $studentGroupKey,
        public iterable $disciplineKeys,
    ) {
    }
}
