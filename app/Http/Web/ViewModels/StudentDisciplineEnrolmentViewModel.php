<?php

declare(strict_types=1);

namespace App\Http\Web\ViewModels;

final readonly class StudentDisciplineEnrolmentViewModel
{
    function __construct(
        public mixed $disciplineKey,
        public string $disciplineName,
        public string|null $disciplineAbbreviation,
        public mixed $educatorKey,
        public string $educatorName,
        public mixed $studentGroupKey,
        public string $studentGroupName,
        public string $enroledAt,
    ) {
    }
}
