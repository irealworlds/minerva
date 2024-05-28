<?php

declare(strict_types=1);

namespace App\ApplicationServices\Institutions\AddDiscipline;

use App\Core\Contracts\Cqrs\ICommandHandler;

/**
 * @implements ICommandHandler<AddDisciplineToInstitutionCommand>
 */
final readonly class AddDisciplineToInstitutionHandler implements
    ICommandHandler
{
    /**
     * @inheritDoc
     */
    public function __invoke(mixed $command): void
    {
        $alreadyAssociated = $command->institution
            ->disciplines()
            ->where('id', $command->discipline->getKey())
            ->exists();
        if (!$alreadyAssociated) {
            $command->institution->disciplines()->attach($command->discipline);
        }
    }
}
