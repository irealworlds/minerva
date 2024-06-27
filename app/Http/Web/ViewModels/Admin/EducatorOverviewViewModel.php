<?php

declare(strict_types=1);

namespace App\Http\Web\ViewModels\Admin;

final readonly class EducatorOverviewViewModel
{
    public function __construct(
        public mixed $key,
        public string $username,
        public string $fullName,
        public string $email,
        public string $pictureUri,
        public string $createdAt,
    ) {
    }
}
