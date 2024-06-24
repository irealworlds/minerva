<?php

declare(strict_types=1);

namespace App\Http\Api\Dtos\Educator;

final readonly class StudentDisciplineEnrolmentDto
{
    function __construct(
        public mixed $key,
        public mixed $disciplineKey,
        public string $disciplineName,
        public mixed $studentGroupKey,
        public string $studentGroupName,
        public mixed $studentKey,
        public string $studentName,
        public string $studentPictureUri,
    ) {
    }
}
