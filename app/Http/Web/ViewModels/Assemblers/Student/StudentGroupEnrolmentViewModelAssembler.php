<?php

declare(strict_types=1);

namespace App\Http\Web\ViewModels\Assemblers\Student;

use App\Core\Models\{Institution, StudentGroup, StudentGroupEnrolment};
use App\Http\Web\ViewModels\Student\StudentGroupEnrolmentViewModel;

final readonly class StudentGroupEnrolmentViewModelAssembler
{
    public function assemble(
        StudentGroupEnrolment $enrolment,
    ): StudentGroupEnrolmentViewModel {
        return new StudentGroupEnrolmentViewModel(
            key: $enrolment->getRouteKey(),
            studentGroupKey: $enrolment->studentGroup->getRouteKey(),
            studentGroupName: $enrolment->studentGroup->name,
            studentGroupAncestors: $enrolment->studentGroup
                ->ancestors()
                ->get()
                ->map(
                    static fn (StudentGroup $ancestor) => (object) [
                        'key' => $ancestor->getRouteKey(),
                        'name' => $ancestor->name,
                    ],
                ),
            institutionKey: $enrolment->studentGroup->parentInstitution->getRouteKey(),
            institutionName: $enrolment->studentGroup->parentInstitution->name,
            institutionPictureUri: $enrolment->studentGroup->parentInstitution->getFirstMediaUrl(
                Institution::EmblemPictureMediaCollection,
            ),
            institutionAncestors: $enrolment->studentGroup->parentInstitution
                ->ancestors()
                ->get()
                ->map(
                    static fn (Institution $ancestor) => (object) [
                        'key' => $ancestor->getRouteKey(),
                        'name' => $ancestor->name,
                    ],
                ),
        );
    }
}
