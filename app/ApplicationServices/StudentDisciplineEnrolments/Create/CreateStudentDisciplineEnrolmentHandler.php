<?php

declare(strict_types=1);

namespace App\ApplicationServices\StudentDisciplineEnrolments\Create;

use App\Core\Contracts\Cqrs\ICommandHandler;
use App\Core\Models\StudentDisciplineEnrolment;
use Throwable;

/**
 * @implements ICommandHandler<CreateStudentDisciplineEnrolmentCommand>
 */
final readonly class CreateStudentDisciplineEnrolmentHandler implements
    ICommandHandler
{
    /**
     * @inheritDoc
     * @throws Throwable
     */
    public function __invoke(mixed $command): void
    {
        /** @var StudentDisciplineEnrolment $enrolment */
        $enrolment = StudentDisciplineEnrolment::query()->make();
        $enrolment->student_group_enrolment_id =
            $command->studentGroupEnrolmentKey;
        $enrolment->discipline_id = $command->disciplineKey;
        $enrolment->educator_id = $command->educatorKey;
        $enrolment->saveOrFail();
    }
}
