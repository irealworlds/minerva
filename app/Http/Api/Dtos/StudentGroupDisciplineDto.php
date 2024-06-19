<?php

declare(strict_types=1);

namespace App\Http\Api\Dtos;

use App\Core\Models\{Educator, StudentGroupDiscipline};

final readonly class StudentGroupDisciplineDto
{
    /**
     * @param iterable<StudentGroupDisciplineEducatorDto> $educators
     */
    public function __construct(
        public mixed $id,
        public string $name,
        public string|null $abbreviation,
        public iterable $educators,
    ) {
    }

    /**
     * Generate a new dto instance from a {@link $model model}.
     */
    public static function fromModel(
        StudentGroupDiscipline $model,
    ): StudentGroupDisciplineDto {
        return new StudentGroupDisciplineDto(
            id: $model->discipline->getRouteKey(),
            name: $model->discipline->name,
            abbreviation: $model->discipline->abbreviation,
            educators: $model
                ->educators()
                ->get()
                ->map(
                    static fn (
                        Educator $educator,
                    ) => StudentGroupDisciplineEducatorDto::fromModel(
                        $educator,
                    ),
                ),
        );
    }
}
