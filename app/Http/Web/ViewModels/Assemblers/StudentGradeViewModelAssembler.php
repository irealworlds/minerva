<?php

declare(strict_types=1);

namespace App\Http\Web\ViewModels\Assemblers;

use App\Core\Models\StudentDisciplineGrade;
use App\Http\Web\ViewModels\StudentGradeViewModel;

final readonly class StudentGradeViewModelAssembler
{
    public function assemble(
        StudentDisciplineGrade $grade,
    ): StudentGradeViewModel {
        return new StudentGradeViewModel(
            key: $grade->getRouteKey(),
            awardedBy: (object) [
                'name' => $grade->educator->identity->name->getFullName(),
            ],
            awardedPoints: $grade->awarded_points,
            maximumPoints: $grade->maximum_points,
            notes: $grade->notes,
            awardedAt: $grade->awarded_at->toIso8601String(),
        );
    }
}
