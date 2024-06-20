<?php

declare(strict_types=1);

namespace App\ApplicationServices\Educators\RemoveStudentGroupDiscipline;

use App\Core\Contracts\Cqrs\ICommandHandler;
use App\Core\Models\StudentGroupDisciplineEducator;

/**
 * @implements ICommandHandler<RemoveEducatorStudentGroupDisciplineCommand>
 */
final readonly class RemoveEducatorStudentGroupDisciplineHandler implements
    ICommandHandler
{
    /**
     * @inheritDoc
     */
    public function __invoke(mixed $command): void
    {
        StudentGroupDisciplineEducator::query()
            ->where(
                (new StudentGroupDisciplineEducator())
                    ->educator()
                    ->getForeignKeyName(),
                $command->educatorKey,
            )
            ->where(
                (new StudentGroupDisciplineEducator())
                    ->studentGroup()
                    ->getForeignKeyName(),
                $command->studentGroupKey,
            )
            ->where(
                (new StudentGroupDisciplineEducator())
                    ->discipline()
                    ->getForeignKeyName(),
                $command->disciplineKey,
            )
            ->delete();
    }
}
