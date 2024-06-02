<?php

declare(strict_types=1);

namespace App\Http\Web\ViewModels;

use App\Core\Models\EducatorInvitation;

final readonly class OutstandingEducatorInvitationViewModel
{
    /**
     * @param iterable<string> $roles
     */
    public function __construct(
        public mixed $id,
        public string $name,
        public string $email,
        public string $pictureUri,
        public iterable $roles,
        public string $expiresAt,
    ) {
    }

    public static function fromModel(
        EducatorInvitation $model,
    ): OutstandingEducatorInvitationViewModel {
        return new OutstandingEducatorInvitationViewModel(
            id: $model->getRouteKey(),
            name: $model->invitedEducator->identity->email, // todo replace with name
            email: $model->invitedEducator->identity->email,
            pictureUri: 'https://ui-avatars.com/api/?name=' .
                urlencode($model->invitedEducator->identity->email) .
                '&background=random&size=128',
            roles: $model->roles,
            expiresAt: $model->expired_at->toIso8601String(),
        );
    }
}
