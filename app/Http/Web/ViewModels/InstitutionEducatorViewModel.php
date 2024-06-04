<?php

declare(strict_types=1);

namespace App\Http\Web\ViewModels;

use App\Core\Models\InstitutionEducator;

final readonly class InstitutionEducatorViewModel
{
    /**
     * @param iterable<string> $roles
     */
    public function __construct(
        public mixed $id,
        public string $name,
        public string $email,
        public iterable $roles,
        public string $pictureUri,
        public string $createdAt,
    ) {
    }

    public static function fromModel(
        InstitutionEducator $model,
    ): InstitutionEducatorViewModel {
        return new InstitutionEducatorViewModel(
            id: $model->educator->getRouteKey(),
            name: $model->educator->identity->name->getFullName(),
            email: $model->educator->identity->email,
            roles: $model->roles,
            pictureUri: 'https://ui-avatars.com/api/?name=' .
                urlencode($model->educator->identity->email) .
                '&background=random&size=128',
            createdAt: $model->created_at->toIso8601String(),
        );
    }
}
