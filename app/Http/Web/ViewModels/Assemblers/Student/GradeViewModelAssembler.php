<?php

declare(strict_types=1);

namespace App\Http\Web\ViewModels\Assemblers\Student;

use App\Core\Models\{Identity, StudentDisciplineGrade};
use App\Http\Web\ViewModels\Student\GradeViewModel;

final readonly class GradeViewModelAssembler
{
    public function assemble(StudentDisciplineGrade $grade): GradeViewModel
    {
        return new GradeViewModel(
            gradeKey: $grade->getRouteKey(),
            awardedPoints: $grade->awarded_points,
            maximumPoints: $grade->maximum_points,
            notes: $grade->notes,
            disciplineKey: $grade->discipline->getRouteKey(),
            disciplineName: $grade->discipline->name,
            educatorKey: $grade->educator->getRouteKey(),
            educatorName: $grade->educator->identity->name->getFullName(),
            educatorPictureUri: $grade->educator->identity->getFirstMediaUrl(
                Identity::ProfilePictureMediaCollection,
            ),
            awardedAt: $grade->awarded_at->toIso8601String(),
        );
    }
}
