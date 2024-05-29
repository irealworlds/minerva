<?php

declare(strict_types=1);

namespace App\ApplicationServices\StudentGroups\RemoveDiscipline;

use App\Core\Contracts\Cqrs\ICommandHandler;

/**
 * @implements ICommandHandler<RemoveDisciplineFromStudentGroupCommand>
 */
final readonly class RemoveDisciplineFromStudentGroupHandler implements
    ICommandHandler
{
    /**
     * @inheritDoc
     */
    public function __invoke(mixed $command): void
    {
        $command->group->disciplines()->detach($command->discipline->getKey());
    }
}
