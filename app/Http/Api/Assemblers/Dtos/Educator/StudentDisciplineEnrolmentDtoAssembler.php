<?php

declare(strict_types=1);

namespace App\Http\Api\Assemblers\Dtos\Educator;

use App\Core\Models\Identity;
use App\Core\Models\StudentDisciplineEnrolment;
use App\Http\Api\Dtos\Educator\StudentDisciplineEnrolmentDto;

final readonly class StudentDisciplineEnrolmentDtoAssembler
{
    public function assemble(
        StudentDisciplineEnrolment $enrolment,
    ): StudentDisciplineEnrolmentDto {
        return new StudentDisciplineEnrolmentDto(
            key: $enrolment->getRouteKey(),
            disciplineKey: $enrolment->discipline->getRouteKey(),
            disciplineName: $enrolment->discipline->name,
            studentGroupKey: $enrolment->studentGroupEnrolment->studentGroup->getRouteKey(),
            studentGroupName: $enrolment->studentGroupEnrolment->studentGroup
                ->name,
            studentKey: $enrolment->studentGroupEnrolment->studentRegistration->getRouteKey(),
            studentName: $enrolment->studentGroupEnrolment->studentRegistration->identity->name->getFullName(),
            studentPictureUri: $enrolment->studentGroupEnrolment->studentRegistration->identity->getFirstMediaUrl(
                Identity::ProfilePictureMediaCollection,
            ),
        );
    }
}
