<?php

declare(strict_types=1);

namespace App\Http\ViewModels\ViewModels;

use App\Core\Models\Institution;

final readonly class InstitutionViewModel
{
    public function __construct(
        public mixed $id,
        public string $name,
        public string|null $website,
        public string|null $pictureUri,
    ) {
    }

    /**
     * Create a new view model from a domain model.
     *
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
