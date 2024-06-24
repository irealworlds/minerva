<?php

declare(strict_types=1);

namespace App\ApplicationServices\StudentDisciplineGrades\Create;

use App\Core\Contracts\Cqrs\ICommandHandler;
use App\Core\Models\StudentDisciplineGrade;
use Throwable;

/**
 * @implements ICommandHandler<CreateStudentDisciplineGradeCommand>
 */
final readonly class CreateStudentDisciplineGradeHandler implements
    ICommandHandler
{
    /**
     * @inheritDoc
     * @throws Throwable
     */
    public function __invoke(mixed $command): void
    {
        /** @var StudentDisciplineGrade $grade */
        $grade = StudentDisciplineGrade::query()->make();
        $grade->student_id = $command->studentKey;
        $grade->student_group_id = $command->studentGroupKey;
        $grade->discipline_id = $command->disciplineKey;
        $grade->awarded_points = $command->awardedPoints;
        $grade->maximum_points = $command->maximumPoints;
        $grade->notes = $command->notes ?? '';
        $grade->educator_id = $command->educatorKey;
        $grade->awarded_at = $command->awardedAt;
        $grade->saveOrFail();
    }
}
