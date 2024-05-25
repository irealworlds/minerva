<?php

namespace App\Http\ViewModels\ViewModels;

use App\Core\Enums\Permission;
use Illuminate\Support\Enumerable;

final readonly class AuthenticatedUserViewModel
{
    /**
     * @param mixed $id
     * @param string $email
     * @param bool $emailVerified
     * @param string $pictureUri
     * @param Enumerable<int, Permission> $permissions
     */
    function __construct(
        public mixed $id,
        public string $email,
        public bool $emailVerified,
        public string $pictureUri,
        public Enumerable $permissions
    ) {
    }
}
