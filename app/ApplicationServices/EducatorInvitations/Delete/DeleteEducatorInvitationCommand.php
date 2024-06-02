<?php

declare(strict_types=1);

namespace App\ApplicationServices\EducatorInvitations\Delete;

use App\Core\Contracts\Cqrs\ICommand;
use App\Core\Models\EducatorInvitation;

final readonly class DeleteEducatorInvitationCommand implements ICommand
{
    public function __construct(public EducatorInvitation $invitation)
    {
    }
}
