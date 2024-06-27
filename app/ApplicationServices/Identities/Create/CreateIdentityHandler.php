<?php

declare(strict_types=1);

namespace App\ApplicationServices\Identities\Create;

use App\ApplicationServices\Identities\SendSetPasswordNotification\SendIdentitySetPasswordNotificationCommand;
use App\Core\Contracts\Cqrs\{ICommandBus, ICommandHandler};
use App\Core\Models\Identity;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Contracts\Hashing\Hasher;
use Throwable;

/**
 * @implements ICommandHandler<CreateIdentityCommand>
 */
final readonly class CreateIdentityHandler implements ICommandHandler
{
    public function __construct(
        private Hasher $_hasher,
        private EventDispatcher $_eventDispatcher,
        private ICommandBus $_commandBus,
    ) {
    }

    /**
     * @inheritDoc
     * @throws Throwable
     */
    public function __invoke(mixed $command): void
    {
        /** @var Identity $identity */
        $identity = Identity::query()->make();
        $identity->username = $command->username;
        $identity->name = $command->name;
        $identity->email = $command->email;
        if ($command->password->hasValue()) {
            $identity->password = $this->_hasher->make(
                $command->password->value,
            );
        }
        $identity->saveOrFail();

        // Dispatch the registered event
        $this->_eventDispatcher->dispatch(new Registered($identity));

        // If no password was provided, send a notification to set the password
        if (empty($identity->password)) {
            $this->_commandBus->dispatch(
                new SendIdentitySetPasswordNotificationCommand(
                    identity: $identity,
                ),
            );
        }
    }
}
