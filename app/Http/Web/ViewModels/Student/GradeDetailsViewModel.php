<?php

declare(strict_types=1);

namespace App\Http\Web\ViewModels\Student;

final readonly class GradeDetailsViewModel
{
    public function __construct(
        public mixed $gradeKey,
        public float $awardedPoints,
        public float $maximumPoints,
        public string $notes,
        public mixed $disciplineKey,
        public string $disciplineName,
        public mixed $educatorKey,
        public string $educatorName,
        public string $educatorPictureUri,
        public string $awardedAt,
    ) {
    }
}
