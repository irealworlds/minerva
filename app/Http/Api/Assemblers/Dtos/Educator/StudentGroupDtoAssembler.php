<?php

declare(strict_types=1);

namespace App\Http\Api\Assemblers\Dtos\Educator;

use App\Core\Models\Institution;
use App\Core\Models\StudentGroup;
use App\Http\Api\Dtos\Educator\StudentGroupDto;

final readonly class StudentGroupDtoAssembler
{
    public function assemble(StudentGroup $model): StudentGroupDto
    {
        return new StudentGroupDto(
            id: $model->getRouteKey(),
            name: $model->name,
            ancestors: $this->getAncestors($model),
        );
    }

    /**
     * @return iterable<object{id: mixed, type: 'institution'|'studentGroup', name: string}>
     */
    protected function getAncestors(StudentGroup|Institution $model): iterable
    {
        $parent = $model->parent;

        if (
            !($parent instanceof StudentGroup) &&
            !($parent instanceof Institution)
        ) {
            return [];
        }

        return [
            ...$this->getAncestors($parent),
            (object) [
                'id' => $parent->getRouteKey(),
                'type' =>
                    $parent instanceof Institution
                        ? 'institution'
                        : 'studentGroup',
                'name' => $parent->name,
            ],
        ];
    }
}
