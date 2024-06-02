<?php

declare(strict_types=1);

namespace App\ApplicationServices\EducatorInvitations\Create;

use App\Core\Contracts\Cqrs\ICommand;
use App\Core\Models\{Educator, Identity, Institution};

final readonly class CreateEducatorInvitationCommand implements ICommand
{
    /**
     * @param iterable<string> $roles
     */
    public function __construct(
        public Institution $institution,
        public Educator $educator,
        public Identity $inviter,
        public iterable $roles,
    ) {
    }
}
