<?php

declare(strict_types=1);

namespace App\Http\Web\ViewModels\Student;

final readonly class StudentDisciplineEnrolmentViewModel
{
    public function __construct(
        public mixed $key,
        public mixed $disciplineKey,
        public string $disciplineName,
        public string|null $disciplineAbbreviation,
        public string $disciplinePictureUri,
        public mixed $educatorKey,
        public string $educatorName,
        public string $educatorPictureUri,
        public float $gradesCount,
        public float|null $averageGrade,
    ) {
    }
}
