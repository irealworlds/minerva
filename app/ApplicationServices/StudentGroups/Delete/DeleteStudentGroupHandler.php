<?php

declare(strict_types=1);

namespace App\ApplicationServices\StudentGroups\Delete;

use App\Core\Contracts\Cqrs\ICommandHandler;
use App\Core\Contracts\Services\IStudentGroupService;
use App\Core\Exceptions\InvalidOperationException;
use Throwable;

/**
 * @implements ICommandHandler<DeleteStudentGroupCommand>
 */
final readonly class DeleteStudentGroupHandler implements ICommandHandler
{
    public function __construct(private IStudentGroupService $_studentGroupService)
    {
    }

    /**
     * @inheritDoc
     * @throws InvalidOperationException
     * @throws Throwable
     */
    public function __invoke(mixed $command): void
    {
        // Make sure the student group is actually deletable
        $canBeDeleted = $command->initiator
            ? $this->_studentGroupService->canBeDeletedByIdentity(
                $command->studentGroup,
                $command->initiator,
            )
            : $this->_studentGroupService->canBeDeleted($command->studentGroup);
        if (!$canBeDeleted) {
            throw new InvalidOperationException(
                'Student group [' .
                    $command->studentGroup->getKey() .
                    '] cannot be deleted.',
            );
        }

        // Perform the operation
        $command->studentGroup->deleteOrFail();
    }
}
