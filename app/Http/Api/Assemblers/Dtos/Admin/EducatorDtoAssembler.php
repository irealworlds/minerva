<?php

declare(strict_types=1);

namespace App\Http\Api\Assemblers\Dtos\Admin;

use App\Core\Models\Educator;
use App\Http\Api\Dtos\Admin\EducatorDto;

final readonly class EducatorDtoAssembler
{
    public function assemble(Educator $educator): EducatorDto
    {
        return new EducatorDto(
            key: $educator->getRouteKey(),
            name: $educator->identity->name->getFullName(),
        );
    }
}
