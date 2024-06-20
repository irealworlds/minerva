<?php

declare(strict_types=1);

namespace App\Http\Web\ViewModels;

use App\Core\Models\InstitutionEducator;
use App\Http\Api\Dtos\EducatorTaughtDisciplineDto;

final readonly class InstitutionEducatorViewModel
{
    /**
     * @param iterable<string> $roles
     * @param iterable<EducatorTaughtDisciplineDto> $disciplines
     */
    public function __construct(
        public mixed $id,
        public string $name,
        public string $email,
        public iterable $roles,
        public iterable $disciplines,
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
            disciplines: [],
            pictureUri: 'https://ui-avatars.com/api/?name=' .
                urlencode($model->educator->identity->name->getFullName()) .
                '&background=random&size=128',
            createdAt: $model->created_at->toIso8601String(),
        );
    }
}
