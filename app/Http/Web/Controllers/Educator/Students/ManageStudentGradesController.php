<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Educator\Students;

use App\ApplicationServices\StudentDisciplineEnrolments\ListByStudent\ListStudentDisciplineEnrolmentsQuery;
use App\ApplicationServices\StudentDisciplineGrades\List\ListStudentDisciplineGradesQuery;
use App\Core\Contracts\Cqrs\IQueryBus;
use App\Core\Models\Educator;
use App\Core\Models\Identity;
use App\Core\Models\StudentDisciplineEnrolment;
use App\Core\Models\StudentDisciplineGrade;
use App\Core\Models\StudentRegistration;
use App\Core\Optional;
use App\Http\Web\Controllers\Controller;
use App\Http\Web\ViewModels\Assemblers\StudentDisciplineEnrolmentViewModelAssembler;
use App\Http\Web\ViewModels\Assemblers\StudentGradeViewModelAssembler;
use Codestage\Authorization\Attributes\Authorize;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Auth\Factory;
use Illuminate\Http\Request;
use Inertia\Response as InertiaResponse;
use Inertia\ResponseFactory;
use Spatie\RouteAttributes\Attributes\Get;

final readonly class ManageStudentGradesController extends Controller
{
    function __construct(
        private IQueryBus $_queryBus,
        private ResponseFactory $_inertia,
        private Factory $_authManager,
        private StudentDisciplineEnrolmentViewModelAssembler $_taughtDisciplineViewModelAssembler,
        private StudentGradeViewModelAssembler $_studentGradeViewModelAssembler,
    ) {
    }

    /**
     * @throws AuthorizationException
     */
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
        $educator = $this->getAuthenticatedEducatorProfile();

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
                    fn(
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
                fn(
                    StudentDisciplineEnrolment $enrolment,
                ) => $this->_taughtDisciplineViewModelAssembler->assemble(
                    $enrolment,
                ),
            ),
            'grades' => $grades,
            'selectedDisciplineKey' => $request->string('disciplineKey'),
        ]);
    }

    /**
     * @throws AuthorizationException
     */
    protected function getAuthenticatedEducatorProfile(): Educator
    {
        /** @var Identity $identity */
        $identity = $this->_authManager->guard()->user();

        $educator = $identity->educatorProfile;

        if (empty($educator)) {
            throw new AuthorizationException();
        }
        return $educator;
    }
}
