<?php

declare(strict_types=1);

namespace App\Http\Api\Dtos\Admin;

use App\Core\Models\StudentRegistration;

final readonly class StudentRegistrationDto
{
    public function __construct(
        public mixed $id,
        public string $name,
        public string $pictureUri,
    ) {
    }

    public static function fromModel(
        StudentRegistration $registration,
    ): StudentRegistrationDto {
        return new StudentRegistrationDto(
            id: $registration->getRouteKey(),
            name: $registration->identity->name->getFullName(),
            pictureUri: 'https://ui-avatars.com/api/?name=' .
                urlencode($registration->identity->name->getFullName()) .
                '&background=random&size=128',
        );
    }
}
