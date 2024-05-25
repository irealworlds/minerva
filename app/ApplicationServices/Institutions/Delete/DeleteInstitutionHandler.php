<?php

declare(strict_types=1);

namespace App\ApplicationServices\Institutions\Delete;

use App\Core\Contracts\Cqrs\ICommandHandler;

/**
 * @implements ICommandHandler<DeleteInstitutionCommand>
 */
final readonly class DeleteInstitutionHandler implements ICommandHandler
{
    /**
     * @inheritDoc
     */
    public function __invoke(mixed $command): void
    {
        $command->institution->deleteOrFail();
    }
}
