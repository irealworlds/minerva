<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Educator\Students;

use App\ApplicationServices\StudentDisciplineEnrolments\ListByStudent\ListStudentDisciplineEnrolmentsQuery;
use App\ApplicationServices\StudentDisciplineGrades\List\ListStudentDisciplineGradesQuery;
use App\Core\Contracts\Cqrs\IQueryBus;
use App\Core\Models\{
    Identity,
    StudentDisciplineEnrolment,
    StudentDisciplineGrade,
    StudentRegistration,
};
use App\Core\Optional;
use App\Http\Web\Controllers\Controller;
use App\Http\Web\ViewModels\Assemblers\{
    StudentDisciplineEnrolmentViewModelAssembler,
    StudentGradeViewModelAssembler,
};
use Codestage\Authorization\Attributes\Authorize;
use Illuminate\Http\Request;
use Inertia\{Response as InertiaResponse, ResponseFactory};
use Spatie\RouteAttributes\Attributes\Get;

final readonly class ManageStudentGradesController extends Controller
{
    public function __construct(
        private IQueryBus $_queryBus,
        private ResponseFactory $_inertia,
        private StudentDisciplineEnrolmentViewModelAssembler $_taughtDisciplineViewModelAssembler,
        private StudentGradeViewModelAssembler $_studentGradeViewModelAssembler,
    ) {
    }

    #[
        Get(
            '/Educator/Students/{student}/Grades',
            name: 'educator.students.manage.grades',
        ),
    ]
    #[Authorize]
    public function __invoke(
        Request $request,
        StudentRegistration $student,
    ): InertiaResponse {
        $taughtDisciplines = $this->_queryBus->dispatch(
            new ListStudentDisciplineEnrolmentsQuery(
                studentRegistrationKey: $student->getKey(),
                disciplineKey: Optional::empty(),
            ),
        );

        $grades = null;
        if ($request->filled('disciplineKey')) {
            $grades = $this->_queryBus
                ->dispatch(
                    new ListStudentDisciplineGradesQuery(
                        studentRegistrationKeys: Optional::of([
                            $student->getKey(),
                        ]),
                        disciplineKeys: Optional::of([
                            $request->string('disciplineKey'),
                        ]),
                    ),
                )
                ->map(
                    fn (
                        StudentDisciplineGrade $grade,
                    ) => $this->_studentGradeViewModelAssembler->assemble(
                        $grade,
                    ),
                );
        }

        return $this->_inertia->render('Educator/Students/ManageGrades', [
            'student' => [
                'key' => $student->getRouteKey(),
                'name' => $student->identity->name->getFullName(),
                'pictureUri' => $student->identity->getFirstMediaUrl(
                    Identity::ProfilePictureMediaCollection,
                ),
            ],

            'taughtDisciplines' => $taughtDisciplines->map(
                fn (
                    StudentDisciplineEnrolment $enrolment,
                ) => $this->_taughtDisciplineViewModelAssembler->assemble(
                    $enrolment,
                ),
            ),
            'grades' => $grades,
            'selectedDisciplineKey' => $request->string('disciplineKey'),
        ]);
    }
}
