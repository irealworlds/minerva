<?php

declare(strict_types=1);

namespace App\ApplicationServices\Institutions\UpdateDetails;

use App\Core\Contracts\Cqrs\ICommandHandler;
use Throwable;

/**
 * @implements ICommandHandler<UpdateInstitutionDetailsCommand>
 */
final readonly class UpdateInstitutionDetailsHandler implements ICommandHandler
{
    /**
     * @inheritDoc
     *
     * @throws Throwable
     */
    public function __invoke(mixed $command): void
    {
        if ($command->name->hasValue()) {
            $command->institution->name = $command->name->value;
        }

        if ($command->website->hasValue()) {
            $command->institution->website = $command->website->value;
        }

        if ($command->institution->isDirty()) {
            $command->institution->saveOrFail();
        }
    }
}
