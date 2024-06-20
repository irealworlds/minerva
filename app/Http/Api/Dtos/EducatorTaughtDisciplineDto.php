<?php

declare(strict_types=1);

namespace App\Http\Api\Dtos;

final readonly class EducatorTaughtDisciplineDto
{
    public function __construct(
        public mixed $educatorKey,
        public string $educatorName,
        public mixed $disciplineKey,
        public string $disciplineName,
        public mixed $studentGroupKey,
        public string $studentGroupName,
    ) {
    }
}
