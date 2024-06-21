<?php

declare(strict_types=1);

namespace App\Core\Services;

use App\Core\Contracts\Services\IEducatorInvitationService;
use App\Core\Models\{Educator, EducatorInvitation, Identity, Institution};
use App\Core\Notifications\NewEducatorInvitationNotification;
use App\Http\Web\Controllers\Educator\Invitations\ReadEducatorInvitationController;
use Carbon\Carbon;
use Illuminate\Contracts\Notifications\Dispatcher;
use Illuminate\Contracts\Routing\UrlGenerator;

final readonly class EducatorInvitationService implements
    IEducatorInvitationService
{
    public function __construct(
        private Dispatcher $_notificationDispatcher,
        private UrlGenerator $_urlGenerator,
    ) {
    }

    public function createInvitation(
        Institution $institution,
        Educator $educator,
        Identity $inviter,
        iterable $roles = [],
    ): EducatorInvitation {
        /** @var EducatorInvitation $invitation */
        $invitation = EducatorInvitation::query()->make();
        $invitation->expired_at = Carbon::now()->addMonth();
        $invitation->institution_id = $institution->getKey();
        $invitation->invited_educator_id = $educator->getKey();
        $invitation->inviter_name = $inviter->name->getFullName();
        $invitation->inviter_email = $inviter->email;
        $invitation->roles = $roles;
        $invitation->inviter_id = $inviter->getKey();
        $invitation->saveOrFail();

        return $invitation;
    }

    public function dispatchInvitationNotification(
        EducatorInvitation $invitation,
    ): void {
        // Generate a URI for the invitation
        $uri = $this->_urlGenerator->action(
            action: ReadEducatorInvitationController::class,
            parameters: [
                'invitation' => $invitation->getRouteKey(),
            ],
        );

        // Create notification
        $notification = new NewEducatorInvitationNotification(
            inviterName: $invitation->inviter_name,
            institutionId: $invitation->institution->getRouteKey(),
            institutionName: $invitation->institution->name,
            invitationUri: $uri,
        );

        // Dispatch notification to the invited educator
        $this->_notificationDispatcher->send(
            notifiables: $invitation->invitedEducator->identity,
            notification: $notification,
        );
    }
}
