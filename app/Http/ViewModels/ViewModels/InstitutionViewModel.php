<?php

namespace App\Http\ViewModels\ViewModels;

use App\Core\Models\Institution;

class InstitutionViewModel
{
    /**
     * @param string $id
     * @param string $name
     * @param string|null $website
     */
    function __construct(
        public string $id,
        public string $name,
        public string|null $website,
        public string|null $pictureUri,
    ) {
    }

    /**
     * Create a new view model from a domain model.
     *
     * @param Institution $model
     * @return static
     */
    public static function fromModel(Institution $model): static {
        $pictureUri = $model->getFirstMediaUrl(Institution::EmblemPictureMediaCollection);
        if (empty($pictureUri)) {
            $pictureUri = null;
        }

        return new static(
            id: $model->getRouteKey(),
            name: $model->name,
            website: $model->website,
            pictureUri: $pictureUri,
        );
    }
}
