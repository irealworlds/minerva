<?php

declare(strict_types=1);

namespace App\Http\Api\Dtos\Student;

final readonly class StudentDisciplineEnrolmentDto
{
    public function __construct(
        public mixed $key,
        public mixed $disciplineKey,
        public string $disciplineName,
        public string|null $disciplineAbbreviation,
        public mixed $studentGroupKey,
        public string $studentGroupName,
        public mixed $studentKey,
        public string $studentName,
        public string $studentPictureUri,
    ) {
    }
}
