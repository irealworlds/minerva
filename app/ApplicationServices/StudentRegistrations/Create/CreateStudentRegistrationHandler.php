<?php

declare(strict_types=1);

namespace App\ApplicationServices\StudentRegistrations\Create;

use App\Core\Contracts\Cqrs\ICommandHandler;
use App\Core\Models\{StudentDisciplineEnrolment, StudentGroupEnrolment};
use Illuminate\Database\ConnectionResolverInterface;
use Throwable;

/**
 * @implements ICommandHandler<CreateStudentRegistrationCommand>
 */
final readonly class CreateStudentRegistrationHandler implements ICommandHandler
{
    public function __construct(private ConnectionResolverInterface $_database)
    {
    }

    /**
     * @inheritDoc
     *
     * @throws Throwable
     */
    public function __invoke(mixed $command): void
    {
        $studentGroupKey = $command->studentGroupKey;
        $studentKey = $command->studentKey;
        $disciplines = $command->disciplines;

        $this->_database
            ->connection()
            ->transaction(function () use (
                $studentKey,
                $studentGroupKey,
                $disciplines,
            ): void {
                // Create an enrolment
                /** @var StudentGroupEnrolment $enrolment */
                $enrolment = StudentGroupEnrolment::query()->make();
                $enrolment->student_group_id = $studentGroupKey;
                $enrolment->student_registration_id = $studentKey;
                $enrolment->saveOrFail();

                // Create a registration for each discipline
                foreach ($disciplines as $discipline) {
                    /** @var StudentDisciplineEnrolment $disciplineEnrolment */
                    $disciplineEnrolment = StudentDisciplineEnrolment::query()->make();
                    $disciplineEnrolment->student_registration_id = $studentKey;
                    $disciplineEnrolment->discipline_id =
                        $discipline->disciplineKey;
                    $disciplineEnrolment->educator_id =
                        $discipline->educatorKey;
                    $disciplineEnrolment->saveOrFail();
                }
            });
    }
}
