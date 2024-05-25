<?php

namespace App\Http\ViewModels\ViewModels;

use App\Core\Models\Institution;

final readonly class InstitutionViewModel
{
    /**
     * @param mixed $id
     * @param string $name
     * @param string|null $website
     * @param string|null $pictureUri
     */
    function __construct(
        public mixed $id,
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
    public static function fromModel(Institution $model): InstitutionViewModel
    {
        $pictureUri = $model->getFirstMediaUrl(Institution::EmblemPictureMediaCollection);
        if (empty($pictureUri)) {
            $pictureUri = null;
        }

        return new InstitutionViewModel(
            id: $model->getRouteKey(),
            name: $model->name,
            website: $model->website,
            pictureUri: $pictureUri,
        );
    }
}
