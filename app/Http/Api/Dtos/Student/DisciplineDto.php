<?php

declare(strict_types=1);

namespace App\Http\Api\Dtos\Student;

final readonly class DisciplineDto
{
    public function __construct(
        public mixed $id,
        public string $name,
        public string|null $abbreviation,
    ) {
    }
}
