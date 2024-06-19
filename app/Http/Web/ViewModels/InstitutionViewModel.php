<?php

declare(strict_types=1);

namespace App\Http\Web\ViewModels;

final readonly class InstitutionViewModel
{
    /**
     * @param iterable<object{id: mixed, name: string}> $ancestors
     * @param iterable<object{id: mixed, name: string}> $childInstitutions
     */
    public function __construct(
        public mixed $id,
        public string $name,
        public string|null $website,
        public string|null $pictureUri,
        public iterable $ancestors,
        public int $educatorsCount,
        public int $studentsCount,
        public int $disciplinesCount,
        public iterable $childInstitutions,
        public string $createdAt,
        public string $updatedAt,
    ) {
    }
}
