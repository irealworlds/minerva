<?php

namespace App\Http\ViewModels;

final readonly class AuthenticatedUserViewModel
{
    function __construct(
        public int $id,
        public string $email,
        public bool $emailVerified,
        public string $pictureUri
    ) {
    }
}
