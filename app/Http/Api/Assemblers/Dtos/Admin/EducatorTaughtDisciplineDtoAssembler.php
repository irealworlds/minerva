<?php

declare(strict_types=1);

namespace App\Http\Api\Assemblers\Dtos\Admin;

use App\Core\Models\StudentGroupDisciplineEducator;
use App\Http\Api\Dtos\Admin\EducatorTaughtDisciplineDto;

final readonly class EducatorTaughtDisciplineDtoAssembler
{
    public function assemble(
        StudentGroupDisciplineEducator $model,
    ): EducatorTaughtDisciplineDto {
        return new EducatorTaughtDisciplineDto(
            educatorKey: $model->educator->getRouteKey(),
            educatorName: $model->educator->identity->name->getFullName(),
            disciplineKey: $model->discipline->getRouteKey(),
            disciplineName: $model->discipline->name,
            studentGroupKey: $model->studentGroup->getRouteKey(),
            studentGroupName: $model->studentGroup->name,
        );
    }
}
