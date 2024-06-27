<?php

declare(strict_types=1);

namespace App\ApplicationServices\Identities\SendSetPasswordNotification;

use App\Core\Contracts\Cqrs\ICommandHandler;
use App\Core\Contracts\Services\ISignedUrlGenerator;
use App\Core\Notifications\SetIdentityPasswordNotification;
use App\Http\Web\Controllers\Identity\CreatePasswordController;
use Illuminate\Contracts\Notifications\Dispatcher;
use InvalidArgumentException;
use RuntimeException;

/**
 * @implements ICommandHandler<SendIdentitySetPasswordNotificationCommand>
 */
final readonly class SendIdentitySetPasswordNotificationHandler implements
    ICommandHandler
{
    public function __construct(
        private Dispatcher $_notificationDispatcher,
        private ISignedUrlGenerator $_router,
    ) {
    }

    /**
     * @inheritDoc
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function __invoke(mixed $command): void
    {
        $uri = $this->_router->generateActionUri(
            CreatePasswordController::class,
            [
                'identity' => $command->identity->getRouteKey(),
            ],
        );

        $this->_notificationDispatcher->send(
            [$command->identity],
            new SetIdentityPasswordNotification(uri: $uri),
        );
    }
}
