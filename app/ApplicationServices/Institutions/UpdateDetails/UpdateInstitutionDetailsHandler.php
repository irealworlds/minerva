<?php

namespace App\ApplicationServices\Institutions\UpdateDetails;

use App\Core\Contracts\Cqrs\ICommandHandler;
use Throwable;

/**
 * @implements ICommandHandler<UpdateInstitutionDetailsCommand>
 */
class UpdateInstitutionDetailsHandler implements ICommandHandler
{
    /**
     * @inheritDoc
     * @throws Throwable
     */
    public function __invoke(mixed $command): void
    {
        if ($command->name->hasValue()) {
            $command->institution->name = $command->name->getValue();
        }
        if ($command->website->hasValue()) {
            $command->institution->website = $command->website->getValue();
        }
        if ($command->institution->isDirty()) {
            $command->institution->saveOrFail();
        }
    }
}
