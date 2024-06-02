<?php

declare(strict_types=1);

namespace App\ApplicationServices\EducatorInvitations\Decline;

use App\Core\Contracts\Cqrs\ICommandHandler;
use Carbon\Carbon;
use Throwable;

/**
 * @implements ICommandHandler<DeclineEducatorInvitationCommand>
 */
final readonly class DeclineEducatorInvitationHandler implements ICommandHandler
{
    /**
     * @inheritDoc
     * @throws Throwable
     */
    public function __invoke(mixed $command): void
    {
        $command->invitation->accepted = false;
        $command->invitation->responded_at = new Carbon();
        $command->invitation->saveOrFail();
    }
}
