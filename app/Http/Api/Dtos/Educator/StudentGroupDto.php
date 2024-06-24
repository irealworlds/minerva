<?php

declare(strict_types=1);

namespace App\Http\Api\Dtos\Educator;

final readonly class StudentGroupDto
{
    /**
     * @param iterable<object{id: mixed, type: 'institution'|'studentGroup', name: string}> $ancestors
     */
    public function __construct(
        public mixed $id,
        public string $name,
        public iterable $ancestors,
    ) {
    }
}
