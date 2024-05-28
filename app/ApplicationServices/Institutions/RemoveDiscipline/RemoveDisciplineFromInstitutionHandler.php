<?php

declare(strict_types=1);

namespace App\ApplicationServices\Institutions\RemoveDiscipline;

use App\Core\Contracts\Cqrs\ICommandHandler;

/**
 * @implements ICommandHandler<RemoveDisciplineFromInstitutionCommand>
 */
final readonly class RemoveDisciplineFromInstitutionHandler implements
    ICommandHandler
{
    /**
     * @inheritDoc
     */
    public function __invoke(mixed $command): void
    {
        $command->institution
            ->disciplines()
            ->detach($command->discipline->getKey());
    }
}
