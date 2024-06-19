<?php

declare(strict_types=1);

namespace App\ApplicationServices\StudentRegistrations\Create;

use App\Core\Contracts\Cqrs\ICommand;
use App\Core\Dtos\StudentEnrolmentDisciplineDto;

final readonly class CreateStudentRegistrationCommand implements ICommand
{
    /**
     * @param iterable<StudentEnrolmentDisciplineDto> $disciplines
     */
    public function __construct(
        public mixed $studentKey,
        public mixed $studentGroupKey,
        public iterable $disciplines,
    ) {
    }
}
