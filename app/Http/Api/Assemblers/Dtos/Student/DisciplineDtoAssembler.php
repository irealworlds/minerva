<?php

declare(strict_types=1);

namespace App\Http\Api\Assemblers\Dtos\Student;

use App\Core\Models\Discipline;
use App\Http\Api\Dtos\Student\DisciplineDto;

final readonly class DisciplineDtoAssembler
{
    public function assemble(Discipline $discipline): DisciplineDto
    {
        return new DisciplineDto(
            id: $discipline->getRouteKey(),
            name: $discipline->name,
            abbreviation: $discipline->abbreviation,
        );
    }
}
