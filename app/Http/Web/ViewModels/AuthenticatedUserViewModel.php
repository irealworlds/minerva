<?php

declare(strict_types=1);

namespace App\Http\Web\ViewModels;

use App\Core\Enums\Permission;
use Illuminate\Support\Enumerable;

final readonly class AuthenticatedUserViewModel
{
    /**
     * @param Enumerable<int, Permission> $permissions
     */
    public function __construct(
        public mixed $id,
        public string $email,
        public bool $emailVerified,
        public string $pictureUri,
        public Enumerable $permissions,
    ) {
    }
}
