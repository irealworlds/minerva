<?php

declare(strict_types=1);

namespace App\Http\Api\Dtos;

use App\Core\Models\Discipline;

final readonly class DisciplineDto
{
    public function __construct(
        public mixed $id,
        public string $name,
        public string|null $abbreviation,
    ) {
    }

    public static function fromModel(Discipline $model): DisciplineDto
    {
        return new DisciplineDto(
            id: $model->getRouteKey(),
            name: $model->name,
            abbreviation: $model->abbreviation,
        );
    }
}
