<?php

declare(strict_types=1);

namespace App\Http\Web\ViewModels;

use App\Core\Models\Educator;

final readonly class EducatorSuggestionViewModel
{
    public function __construct(
        public mixed $id,
        public string $name,
        public string $email,
        public int $institutionsCount,
        public string|null $institutionName,
        public string $pictureUri,
    ) {
    }

    public static function fromModel(
        Educator $model,
    ): EducatorSuggestionViewModel {
        return new EducatorSuggestionViewModel(
            id: $model->getRouteKey(),
            name: $model->identity->email, // TODO use actual name
            email: $model->identity->email,
            institutionsCount: $model->institutions()->count(),
            institutionName: $model->pivot?->name,
            pictureUri: 'https://ui-avatars.com/api/?name=' .
                urlencode($model->identity->email) .
                '&background=random&size=128',
        );
    }
}
