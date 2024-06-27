<?php

declare(strict_types=1);

namespace App\Http\Api\Dtos\Admin;

final readonly class EducatorDto
{
    public function __construct(public mixed $key, public string $name)
    {
    }
}
