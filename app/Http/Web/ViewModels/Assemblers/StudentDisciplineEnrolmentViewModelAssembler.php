<?php

declare(strict_types=1);

namespace App\Http\Web\ViewModels\Assemblers;

use App\Core\Models\StudentDisciplineEnrolment;
use App\Http\Web\ViewModels\StudentDisciplineEnrolmentViewModel;

final readonly class StudentDisciplineEnrolmentViewModelAssembler
{
    public function assemble(
        StudentDisciplineEnrolment $enrolment,
    ): StudentDisciplineEnrolmentViewModel {
        return new StudentDisciplineEnrolmentViewModel(
            disciplineKey: $enrolment->discipline->getRouteKey(),
            disciplineName: $enrolment->discipline->name,
            disciplineAbbreviation: $enrolment->discipline->abbreviation,
            educatorKey: $enrolment->educator->getRouteKey(),
            educatorName: $enrolment->educator->identity->name->getFullName(),
            studentGroupKey: $enrolment->studentGroupEnrolment->studentGroup->getRouteKey(),
            studentGroupName: $enrolment->studentGroupEnrolment->studentGroup
                ->name,
            enroledAt: $enrolment->created_at->toIso8601String(),
        );
    }
}
