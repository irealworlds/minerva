<?php

declare(strict_types=1);

namespace App\Http\Web\ViewModels;

use App\Core\Models\Institution;

final readonly class InstitutionViewModel
{
    /**
     * @param iterable<object{id: string, name: string}> $ancestors
     */
    public function __construct(
        public mixed $id,
        public string $name,
        public string|null $website,
        public string|null $pictureUri,
        public iterable $ancestors,
        public string $createdAt,
        public string $updatedAt,
    ) {
    }

    /**
     * Create a new view model from a domain model.
     *
     * @return static
     */
    public static function fromModel(Institution $model): InstitutionViewModel
    {
        $pictureUri = $model->getFirstMediaUrl(
            Institution::EmblemPictureMediaCollection,
        );
        if (empty($pictureUri)) {
            $pictureUri = null;
        }

        /** @return iterable<object{id: string, type: 'institution'|'studentGroup', name: string}> */
        $getAncestors = function (Institution $model) use (
            &$getAncestors,
        ): iterable {
            if ($model->parent === null) {
                return [];
            }

            return [
                ...$getAncestors($model->parent),
                (object) [
                    'id' => $model->parent->getRouteKey(),
                    'name' => $model->parent->name,
                ],
            ];
        };

        /** @var iterable<object{id: string, type: 'institution'|'studentGroup', name: string}> $ancestors */
        $ancestors = $getAncestors($model);

        return new InstitutionViewModel(
            id: $model->getRouteKey(),
            name: $model->name,
            website: $model->website,
            pictureUri: $pictureUri,
            ancestors: $ancestors,
            createdAt: $model->created_at->toIso8601String(),
            updatedAt: $model->updated_at->toIso8601String(),
        );
    }
}
