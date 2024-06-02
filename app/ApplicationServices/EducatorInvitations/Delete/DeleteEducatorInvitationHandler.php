<?php

declare(strict_types=1);

namespace App\ApplicationServices\EducatorInvitations\Delete;

use App\Core\Contracts\Cqrs\ICommandHandler;
use App\Core\Exceptions\InvalidOperationException;
use Throwable;

/**
 * @implements ICommandHandler<DeleteEducatorInvitationCommand>
 */
final readonly class DeleteEducatorInvitationHandler implements ICommandHandler
{
    /**
     * @inheritDoc
     * @throws InvalidOperationException
     * @throws Throwable
     */
    public function __invoke(mixed $command): void
    {
        // If the invitation has expired, return a 410 Gone response
        if ($command->invitation->expired_at->isPast()) {
            throw new InvalidOperationException();
        }

        // If the invitation has already been accepted, return a 410 Gone response
        if ($command->invitation->responded_at !== null) {
            throw new InvalidOperationException();
        }

        $command->invitation->deleteOrFail();
    }
}
