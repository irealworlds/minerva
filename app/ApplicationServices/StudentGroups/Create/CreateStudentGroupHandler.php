<?php

declare(strict_types=1);

namespace App\ApplicationServices\StudentGroups\Create;

use App\Core\Contracts\Cqrs\ICommandHandler;
use App\Core\Models\StudentGroup;
use Throwable;

/**
 * @implements ICommandHandler<CreateStudentGroupCommand>
 */
final readonly class CreateStudentGroupHandler implements ICommandHandler
{
    /**
     * @inheritDoc
     *
     * @throws Throwable
     */
    public function __invoke(mixed $command): void
    {
        /** @var StudentGroup $group */
        $group = StudentGroup::query()->make();
        $group->id = $command->id;
        $group->name = $command->name;
        $group->parent()->associate($command->parent);
        $group->saveOrFail();
    }
}
