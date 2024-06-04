<?php

declare(strict_types=1);

namespace App\ApplicationServices\EducatorInvitations\Accept;

use App\ApplicationServices\Educators\AssociateToInstitution\AssociateEducatorToInstitutionCommand;
use App\Core\Contracts\Cqrs\{ICommandBus, ICommandHandler};
use Carbon\Carbon;
use Illuminate\Database\ConnectionResolverInterface;
use Throwable;

/**
 * @implements ICommandHandler<AcceptEducatorInvitationCommand>
 */
final readonly class AcceptEducatorInvitationHandler implements ICommandHandler
{
    public function __construct(
        private ConnectionResolverInterface $_database,
        private ICommandBus $_commandBus,
    ) {
    }

    /**
     * @inheritDoc
     *
     * @throws Throwable
     */
    public function __invoke(mixed $command): void
    {
        $invitation = $command->invitation;
        $this->_database
            ->connection()
            ->transaction(function () use ($invitation): void {
                // Associate the educator with the organization
                $this->_commandBus->dispatch(
                    new AssociateEducatorToInstitutionCommand(
                        educator: $invitation->invitedEducator,
                        institution: $invitation->institution,
                        roles: $invitation->roles,
                    ),
                );

                // Mark the invitation as accepted
                $invitation->accepted = true;
                $invitation->responded_at = new Carbon();
                $invitation->saveOrFail();
            });
    }
}
