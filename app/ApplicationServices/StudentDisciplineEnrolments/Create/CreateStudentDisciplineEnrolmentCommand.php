<?php

declare(strict_types=1);

namespace App\ApplicationServices\StudentDisciplineEnrolments\Create;

use App\Core\Contracts\Cqrs\ICommand;

final readonly class CreateStudentDisciplineEnrolmentCommand implements ICommand
{
    public function __construct(
        public mixed $studentGroupEnrolmentKey,
        public mixed $disciplineKey,
        public mixed $educatorKey,
    ) {
    }
}
