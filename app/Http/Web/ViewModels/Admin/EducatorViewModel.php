<?php

declare(strict_types=1);

namespace App\Http\Web\ViewModels\Admin;

final readonly class EducatorViewModel
{
    public function __construct(
        public mixed $key,
        public string $fullName,
        public string $directoryName,
        public string $email,
        public string $pictureUri,
        public string $createdAt,
    ) {
    }
}
