<?php

declare(strict_types=1);

namespace App\ApplicationServices\StudentGroupEnrolments\Create;

use App\Core\Contracts\Cqrs\ICommand;
use App\Core\Dtos\StudentEnrolmentDisciplineDto;

final readonly class CreateStudentGroupEnrolmentCommand implements ICommand
{
    /**
     * @param iterable<StudentEnrolmentDisciplineDto> $disciplines
     */
    public function __construct(
        public mixed $studentRegistrationKey,
        public mixed $studentGroupKey,
        public iterable $disciplines,
    ) {
    }
}
