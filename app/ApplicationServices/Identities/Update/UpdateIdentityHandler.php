<?php

declare(strict_types=1);

namespace App\ApplicationServices\Identities\Update;

use App\Core\Contracts\Cqrs\ICommandHandler;
use App\Core\Dtos\PersonalNameDto;
use App\Core\Models\Identity;
use Illuminate\Validation\ValidationException;
use Throwable;

/**
 * @implements ICommandHandler<UpdateIdentityCommand>
 */
class UpdateIdentityHandler implements ICommandHandler
{
    /**
     * @inheritDoc
     *
     * @throws ValidationException
     * @throws Throwable
     */
    public function __invoke(mixed $command): void
    {
        if (
            Identity::query()
                ->whereNot(
                    (new Identity())->getKeyName(),
                    $command->identity->getKey(),
                )
                ->where('email', $command->email)
                ->exists()
        ) {
            throw ValidationException::withMessages([
                'email' => __('validation.unique', ['attribute' => 'email']),
            ]);
        }

        // Update the identity
        $command->identity->name = new PersonalNameDto(
            prefix: $command->namePrefix->hasValue()
                ? (empty($command->namePrefix->value)
                    ? null
                    : $command->namePrefix->value)
                : $command->identity->name->prefix,
            firstName: $command->firstName->hasValue()
                ? $command->firstName->value
                : $command->identity->name->firstName,
            middleNames: $command->middleNames->hasValue()
                ? $command->middleNames->value
                : $command->identity->name->middleNames,
            lastName: $command->lastName->hasValue()
                ? $command->lastName->value
                : $command->identity->name->lastName,
            suffix: $command->nameSuffix->hasValue()
                ? (empty($command->nameSuffix->value)
                    ? null
                    : $command->nameSuffix->value)
                : $command->identity->name->suffix,
        );

        if ($command->email->hasValue()) {
            $command->identity->email = $command->email->value;
        }

        if ($command->identity->isDirty('email')) {
            $command->identity->email_verified_at = null;
        }

        $command->identity->saveOrFail();
    }
}
