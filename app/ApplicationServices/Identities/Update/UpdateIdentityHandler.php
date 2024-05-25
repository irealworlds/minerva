<?php

namespace App\ApplicationServices\Identities\Update;

use App\Core\Contracts\Cqrs\ICommandHandler;
use App\Core\Models\Identity;
use Illuminate\Validation\ValidationException;

/**
 * @implements ICommandHandler<UpdateIdentityCommand>
 */
class UpdateIdentityHandler implements ICommandHandler
{
    /**
     * @inheritDoc
     * @throws ValidationException
     */
    public function __invoke(mixed $command): void
    {
        if (Identity::query()->whereNot((new Identity())->getKeyName())->where("email", $command->email)->exists()) {
            throw ValidationException::withMessages([
                "email" => __("validation.unique", ["attribute" => "email"])
            ]);
        }

        $command->identity->email = $command->email;

        if ($command->identity->isDirty('email')) {
            $command->identity->email_verified_at = null;
        }

        $command->identity->save();
    }
}
