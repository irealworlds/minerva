<?php

declare(strict_types=1);

namespace App\Core\Observers;

use App\Core\Models\Identity;
use App\Core\Services\IdentityUsernameService;
use RuntimeException;

final readonly class IdentityObserver
{
    public function __construct(
        private IdentityUsernameService $_usernameService,
    ) {
    }

    /**
     * Handle the Identity "saving" event.
     *
     * @throws RuntimeException
     */
    public function saving(Identity $identity): void
    {
        if ($identity->isDirty('username')) {
            $identity->normalized_username = $this->_usernameService->normalizeUsername(
                $identity->username,
            );
        }
    }
}
