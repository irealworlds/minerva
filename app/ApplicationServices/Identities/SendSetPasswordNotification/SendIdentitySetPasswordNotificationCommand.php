<?php

declare(strict_types=1);

namespace App\ApplicationServices\Identities\SendSetPasswordNotification;

use App\Core\Contracts\Cqrs\ICommand;
use App\Core\Models\Identity;

final readonly class SendIdentitySetPasswordNotificationCommand implements
    ICommand
{
    public function __construct(public Identity $identity)
    {
    }
}
