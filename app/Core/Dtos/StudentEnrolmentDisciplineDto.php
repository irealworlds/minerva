<?php

declare(strict_types=1);

namespace App\Core\Dtos;

final readonly class StudentEnrolmentDisciplineDto
{
    public function __construct(public mixed $disciplineKey, public mixed $educatorKey)
    {
    }
}
