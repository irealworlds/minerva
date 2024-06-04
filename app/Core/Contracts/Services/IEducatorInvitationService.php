<?php

declare(strict_types=1);

namespace App\Core\Contracts\Services;

use App\Core\Models\{Educator, EducatorInvitation, Identity, Institution};
use Throwable;

interface IEducatorInvitationService
{
    /**
     * Create a new invitation for an {@link $educator educator} to join an {@link $institutaion institution}.
     *
     * @param iterable<string> $roles The roles the educator will have in the institution.
     *
     * @throws Throwable
     */
    public function createInvitation(
        Institution $institution,
        Educator $educator,
        Identity $inviter,
        iterable $roles = [],
    ): EducatorInvitation;

    /**
     * Dispatch a new notification for an {@link $invitation invitation}.
     */
    public function dispatchInvitationNotification(
        EducatorInvitation $invitation,
    ): void;
}
