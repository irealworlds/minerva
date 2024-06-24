<?php

declare(strict_types=1);

namespace App\Http\Api\Assemblers\Dtos\Educator;

use App\Core\Models\Institution;
use App\Http\Api\Dtos\Educator\InstitutionDto;

final readonly class InstitutionDtoAssembler
{
    public function assemble(Institution $institution): InstitutionDto
    {
        $pictureUri = $institution->getFirstMediaUrl(
            Institution::EmblemPictureMediaCollection,
        );
        if (empty($pictureUri)) {
            $pictureUri = null;
        }

        return new InstitutionDto(
            id: $institution->getRouteKey(),
            name: $institution->name,
            pictureUri: $pictureUri,
            ancestors: $this->getAncestors($institution),
        );
    }

    /**
     * @return iterable<object{id: mixed, name: string}>
     */
    protected function getAncestors(Institution $model): iterable
    {
        if ($model->parent === null) {
            return [];
        }

        return [
            ...$this->getAncestors($model->parent),
            (object) [
                'id' => $model->parent->getRouteKey(),
                'name' => $model->parent->name,
            ],
        ];
    }
}
