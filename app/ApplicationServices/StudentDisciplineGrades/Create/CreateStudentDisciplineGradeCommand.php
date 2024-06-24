<?php

declare(strict_types=1);

namespace App\ApplicationServices\StudentDisciplineGrades\Create;

use App\Core\Contracts\Cqrs\ICommand;
use Carbon\Carbon;

final readonly class CreateStudentDisciplineGradeCommand implements ICommand
{
    function __construct(
        public mixed $educatorKey,
        public mixed $studentKey,
        public mixed $studentGroupKey,
        public mixed $disciplineKey,
        public float $awardedPoints,
        public float $maximumPoints,
        public string|null $notes = null,
        public Carbon $awardedAt = new Carbon(),
    ) {
    }
}
