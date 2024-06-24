<?php

declare(strict_types=1);

namespace App\Http\Api\Dtos\Educator;

final readonly class DisciplineDto
{
    function __construct(
        public mixed $id,
        public string $name,
        public string|null $abbreviation,
    ) {
    }
}
