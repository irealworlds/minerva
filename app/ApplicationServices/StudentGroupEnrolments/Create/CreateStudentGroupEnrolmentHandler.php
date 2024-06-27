<?php

declare(strict_types=1);

namespace App\ApplicationServices\StudentGroupEnrolments\Create;

use App\Core\Contracts\Cqrs\ICommandHandler;
use App\Core\Models\{StudentDisciplineEnrolment, StudentGroupEnrolment};
use Illuminate\Database\ConnectionResolverInterface;
use Throwable;

/**
 * @implements ICommandHandler<CreateStudentGroupEnrolmentCommand>
 */
final readonly class CreateStudentGroupEnrolmentHandler implements
    ICommandHandler
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
        $studentKey = $command->studentRegistrationKey;
        $disciplines = $command->disciplines;

        $this->_database
            ->connection()
            ->transaction(function () use (
                $studentKey,
                $studentGroupKey,
                $disciplines,
            ): void {
                // Create an enrolment
                /** @var StudentGroupEnrolment $studentGroupEnrolment */
                $studentGroupEnrolment = StudentGroupEnrolment::query()->make();
                $studentGroupEnrolment->student_group_id = $studentGroupKey;
                $studentGroupEnrolment->student_registration_id = $studentKey;
                $studentGroupEnrolment->saveOrFail();

                // Create a registration for each discipline
                foreach ($disciplines as $discipline) {
                    /** @var StudentDisciplineEnrolment $disciplineEnrolment */
                    $disciplineEnrolment = StudentDisciplineEnrolment::query()->make();
                    $disciplineEnrolment->student_group_enrolment_id = $studentGroupEnrolment->getKey();
                    $disciplineEnrolment->discipline_id =
                        $discipline->disciplineKey;
                    $disciplineEnrolment->educator_id =
                        $discipline->educatorKey;
                    $disciplineEnrolment->saveOrFail();
                }
            });
    }
}
