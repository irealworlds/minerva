<?php

declare(strict_types=1);

namespace App\ApplicationServices\EducatorInvitations\Decline;

use App\Core\Contracts\Cqrs\ICommand;
use App\Core\Models\EducatorInvitation;

final readonly class DeclineEducatorInvitationCommand implements ICommand
{
    public function __construct(public EducatorInvitation $invitation)
    {
    }
}
