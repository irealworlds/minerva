<?php

declare(strict_types=1);

namespace App\ApplicationServices\Educators\AddStudentGroupDisciplines;

use App\Core\Contracts\Cqrs\ICommandHandler;
use App\Core\Models\StudentGroupDisciplineEducator;
use Illuminate\Database\ConnectionResolverInterface;
use Throwable;

/**
 * @implements ICommandHandler<AddStudentGroupDisciplinesToEducatorsCommand>
 */
final readonly class AddStudentGroupDisciplinesToEducatorsHandler implements
    ICommandHandler
{
    public function __construct(private ConnectionResolverInterface $_database)
    {
    }

    /**
     * @inheritDoc
     * @throws Throwable
     */
    public function __invoke(mixed $command): void
    {
        $educatorKey = $command->educatorKey;
        $studentGroupKey = $command->studentGroupKey;
        $disciplineKeys = $command->disciplineKeys;

        $this->_database
            ->connection()
            ->transaction(static function () use (
                $disciplineKeys,
                $educatorKey,
                $studentGroupKey,
            ): void {
                foreach ($disciplineKeys as $disciplineKey) {
                    /** @var StudentGroupDisciplineEducator $association */
                    $association = StudentGroupDisciplineEducator::query()->make();
                    $association->student_group_id = $studentGroupKey;
                    $association->discipline_id = $disciplineKey;
                    $association->educator_id = $educatorKey;
                    $association->saveOrFail();
                }
            });
    }
}
