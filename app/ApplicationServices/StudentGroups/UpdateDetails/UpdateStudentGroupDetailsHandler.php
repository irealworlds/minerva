<?php

declare(strict_types=1);

namespace App\ApplicationServices\StudentGroups\UpdateDetails;

use App\Core\Contracts\Cqrs\ICommandHandler;
use Throwable;

/**
 * @implements ICommandHandler<UpdateStudentGroupDetailsCommand>
 */
final readonly class UpdateStudentGroupDetailsHandler implements ICommandHandler
{
    /**
     * @inheritDoc
     *
     * @throws Throwable
     */
    public function __invoke(mixed $command): void
    {
        if ($command->name->hasValue()) {
            $command->studentGroup->name = $command->name->value;
        }

        if ($command->studentGroup->isDirty()) {
            $command->studentGroup->saveOrFail();
        }
    }
}
