<?php

declare(strict_types=1);

namespace App\ApplicationServices\EducatorInvitations\Create;

use App\Core\Contracts\Cqrs\ICommandHandler;
use App\Core\Contracts\Services\IEducatorInvitationService;
use Throwable;

/**
 * @implements ICommandHandler<CreateEducatorInvitationCommand>
 */
final readonly class CreateEducatorInvitationHandler implements ICommandHandler
{
    public function __construct(
        private IEducatorInvitationService $_invitationService,
    ) {
    }

    /**
     * @inheritDoc
     * @throws Throwable
     */
    public function __invoke(mixed $command): void
    {
        $invitation = $this->_invitationService->createInvitation(
            institution: $command->institution,
            educator: $command->educator,
            inviter: $command->inviter,
            roles: $command->roles,
        );
        $this->_invitationService->dispatchInvitationNotification($invitation);
    }
}
