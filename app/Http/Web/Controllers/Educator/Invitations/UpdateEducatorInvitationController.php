<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Educator\Invitations;

use App\ApplicationServices\EducatorInvitations\Accept\AcceptEducatorInvitationCommand;
use App\ApplicationServices\EducatorInvitations\Decline\DeclineEducatorInvitationCommand;
use App\Core\Contracts\Cqrs\ICommandBus;
use App\Core\Models\EducatorInvitation;
use App\Http\Web\Controllers\Controller;
use Illuminate\Http\{RedirectResponse, Response};
use Illuminate\Routing\Redirector;
use InvalidArgumentException;
use Spatie\RouteAttributes\Attributes\Patch;
use Throwable;

final readonly class UpdateEducatorInvitationController extends Controller
{
    public function __construct(
        private Redirector $_redirector,
        private ICommandBus $_commandBus,
    ) {
    }

    /**
     * @throws InvalidArgumentException
     */
    #[Patch('/Educator/Invitations/{invitation}')]
    public function __invoke(
        UpdateEducatorInvitationRequest $request,
        EducatorInvitation $invitation,
    ): RedirectResponse|Response {
        // If the invitation has expired, return a 410 Gone response
        if ($invitation->expired_at->isPast()) {
            return new Response(status: 410);
        }

        // If the invitation has already been accepted, return a 410 Gone response
        if ($invitation->responded_at !== null) {
            return new Response(status: 410);
        }

        // Build the command
        if ($request->boolean('accepted')) {
            $command = new AcceptEducatorInvitationCommand(
                invitation: $invitation,
            );
        } else {
            $command = new DeclineEducatorInvitationCommand(
                invitation: $invitation,
            );
        }

        // Dispatch the command and return a response
        try {
            $this->_commandBus->dispatch($command);
        } catch (Throwable) {
            return $this->_redirector
                ->back()
                ->with('error', [
                    __('toasts.educatorInvitations.response_failed'),
                ]);
        }

        // Redirect back with a success toast
        return $this->_redirector
            ->back()
            ->with('success', [
                $request->boolean('accepted')
                    ? __('toasts.educatorInvitations.accepted')
                    : __('toasts.educatorInvitations.declined'),
            ]);
    }
}
