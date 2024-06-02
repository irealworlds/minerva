<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\EducatorInvitations;

use App\Core\Models\EducatorInvitation;
use App\Http\Web\ViewModels\ReadEducatorInvitationViewModel;
use Inertia\{Response as InertiaResponse, ResponseFactory};
use Spatie\RouteAttributes\Attributes\Get;

final readonly class ReadEducatorInvitationController
{
    public function __construct(private ResponseFactory $_inertia)
    {
    }

    #[Get('/EducatorInvitations/{invitation}')]
    public function __invoke(EducatorInvitation $invitation): InertiaResponse
    {
        return $this->_inertia->render('EducatorInvitations/Read', [
            'invitation' => new ReadEducatorInvitationViewModel(
                institutionName: $invitation->institution->name,
                roles: $invitation->roles,
                inviterPictureUri: 'https://ui-avatars.com/api/?name=' .
                    urlencode($invitation->inviter_name) .
                    '&background=random&size=128',
                inviterName: $invitation->inviter_name,
                inviterEmail: $invitation->inviter_email,
                expiredAt: $invitation->expired_at->toIso8601String(),
                respondedAt: $invitation->responded_at?->toIso8601String(),
                accepted: $invitation->accepted,
            ),
        ]);
    }
}
