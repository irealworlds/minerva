<?php

declare(strict_types=1);

namespace App\Http\Api\Dtos\Educator;

final readonly class InstitutionDto
{
    /**
     * @param iterable<object{id: mixed, name: string}> $ancestors
     */
    function __construct(
        public mixed $id,
        public string $name,
        public string|null $pictureUri,
        public iterable $ancestors,
    ) {
    }
}
