<?php

declare(strict_types=1);

namespace App\Http\Web\ViewModels;

final readonly class ReadEducatorInvitationViewModel
{
    /**
     * @param iterable<string> $roles
     */
    public function __construct(
        public string $institutionName,
        public iterable $roles,
        public string $inviterPictureUri,
        public string $inviterName,
        public string $inviterEmail,
        public string $expiredAt,
        public string|null $respondedAt,
        public bool $accepted,
    ) {
    }
}
