<?php

declare(strict_types=1);

namespace App\Http\Web\ViewModels;

final readonly class EducatorTaughtStudentViewModel
{
    function __construct(
        public mixed $studentRegistrationId,
        public string $studentName,
        public float|null $currentAverage,
        public float $gradesCount,
    ) {
    }
}
