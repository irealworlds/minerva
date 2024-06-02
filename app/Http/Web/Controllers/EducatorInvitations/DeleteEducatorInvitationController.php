<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\EducatorInvitations;

use App\ApplicationServices\EducatorInvitations\Delete\DeleteEducatorInvitationCommand;
use App\Core\Contracts\Cqrs\ICommandBus;
use App\Core\Exceptions\InvalidOperationException;
use App\Core\Models\EducatorInvitation;
use Codestage\Authorization\Attributes\Authorize;
use Illuminate\Http\{RedirectResponse, Response};
use Illuminate\Routing\Redirector;
use InvalidArgumentException;
use Spatie\RouteAttributes\Attributes\Delete;

final readonly class DeleteEducatorInvitationController
{
    public function __construct(
        private Redirector $_redirector,
        private ICommandBus $_commandBus,
    ) {
    }

    /**
     * @throws InvalidArgumentException
     */
    #[
        Delete(
            '/EducatorInvitations/{invitation}',
            name: 'educator_invitations.delete',
        ),
    ]
    #[Authorize]
    public function __invoke(
        EducatorInvitation $invitation,
    ): RedirectResponse|Response {
        try {
            /** @throws InvalidOperationException */
            $this->_commandBus->dispatch(
                new DeleteEducatorInvitationCommand(invitation: $invitation),
            );
        } catch (InvalidOperationException) {
            return new Response(status: 410);
        }

        return $this->_redirector
            ->back()
            ->with('success', [__('toasts.educatorInvitations.deleted')]);
    }
}
