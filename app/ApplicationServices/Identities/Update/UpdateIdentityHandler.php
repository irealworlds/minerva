<?php

namespace App\ApplicationServices\Identities\Update;

use App\Core\Contracts\Cqrs\ICommandHandler;

/**
 * @implements ICommandHandler<UpdateIdentityCommand>
 */
class UpdateIdentityHandler implements ICommandHandler
{
    /**
     * @inheritDoc
     */
    public function __invoke(mixed $command): void
    {
        $command->identity->email = $command->email;

        if ($command->identity->isDirty('email')) {
            $command->identity->email_verified_at = null;
        }

        $command->identity->save();
    }
}
