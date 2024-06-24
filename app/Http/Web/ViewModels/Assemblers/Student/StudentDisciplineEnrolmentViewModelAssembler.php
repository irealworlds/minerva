<?php

declare(strict_types=1);

namespace App\Http\Web\ViewModels\Assemblers\Student;

use App\ApplicationServices\StudentDisciplineGrades\List\ListStudentDisciplineGradesQuery;
use App\Core\Contracts\Cqrs\IQueryBus;
use App\Core\Models\{Identity, StudentDisciplineEnrolment, StudentDisciplineGrade};
use App\Core\Optional;
use App\Http\Web\ViewModels\Student\StudentDisciplineEnrolmentViewModel;

final readonly class StudentDisciplineEnrolmentViewModelAssembler
{
    public function __construct(private IQueryBus $_queryBus)
    {
    }

    public function assemble(
        StudentDisciplineEnrolment $enrolment,
    ): StudentDisciplineEnrolmentViewModel {
        $grades = $this->_queryBus
            ->dispatch(
                new ListStudentDisciplineGradesQuery(
                    studentRegistrationKeys: Optional::of([
                        $enrolment->studentGroupEnrolment->studentRegistration->getKey(),
                    ]),
                    disciplineKeys: Optional::of([
                        $enrolment->discipline->getKey(),
                    ]),
                    studentGroupKeys: Optional::of([
                        $enrolment->studentGroupEnrolment->studentGroup->getKey(),
                    ]),
                ),
            )
            ->map(
                static fn (
                    StudentDisciplineGrade $grade,
                ) => $grade->awarded_points,
            );

        return new StudentDisciplineEnrolmentViewModel(
            key: $enrolment->getRouteKey(),
            disciplineKey: $enrolment->discipline->getRouteKey(),
            disciplineName: $enrolment->discipline->name,
            disciplineAbbreviation: $enrolment->discipline->abbreviation,
            disciplinePictureUri: 'https://ui-avatars.com/api/?name=' .
                urlencode($enrolment->discipline->name) .
                '&background=random&size=128',
            educatorKey: $enrolment->educator->getRouteKey(),
            educatorName: $enrolment->educator->identity->name->getFullName(),
            educatorPictureUri: $enrolment->educator->identity->getFirstMediaUrl(
                Identity::ProfilePictureMediaCollection,
            ),
            gradesCount: $grades->count(),
            averageGrade: $grades->average(),
        );
    }
}
