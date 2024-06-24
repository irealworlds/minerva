<?php

declare(strict_types=1);

namespace App\Http\Api\Dtos\Admin;

use App\Core\Models\Educator;

final readonly class StudentGroupDisciplineEducatorDto
{
    public function __construct(
        public mixed $educatorId,
        public string $educatorName,
    ) {
    }

    public static function fromModel(
        Educator $model,
    ): StudentGroupDisciplineEducatorDto {
        return new StudentGroupDisciplineEducatorDto(
            educatorId: $model->getKey(),
            educatorName: $model->identity->name->getFullName(),
        );
    }
}
