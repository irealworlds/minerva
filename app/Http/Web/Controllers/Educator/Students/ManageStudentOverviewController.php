<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Educator\Students;

use App\ApplicationServices\StudentDisciplineEnrolments\ListByStudent\ListStudentDisciplineEnrolmentsQuery;
use App\Core\Contracts\Cqrs\IQueryBus;
use App\Core\Models\Educator;
use App\Core\Models\Identity;
use App\Core\Models\StudentDisciplineEnrolment;
use App\Core\Models\StudentRegistration;
use App\Core\Optional;
use App\Http\Web\Controllers\Controller;
use App\Http\Web\ViewModels\Assemblers\StudentDisciplineEnrolmentViewModelAssembler;
use Codestage\Authorization\Attributes\Authorize;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Auth\Factory;
use Inertia\Response as InertiaResponse;
use Inertia\ResponseFactory;
use Spatie\RouteAttributes\Attributes\Get;

final readonly class ManageStudentOverviewController extends Controller
{
    function __construct(
        private IQueryBus $_queryBus,
        private ResponseFactory $_inertia,
        private Factory $_authManager,
        private StudentDisciplineEnrolmentViewModelAssembler $_taughtDisciplineViewModelAssembler,
    ) {
    }

    /**
     * @throws AuthorizationException
     */
    #[
        Get(
            '/Educator/Students/{student}/Overview',
            name: 'educator.students.manage.overview',
        ),
    ]
    #[Authorize]
    public function __invoke(StudentRegistration $student): InertiaResponse
    {
        $educator = $this->getAuthenticatedEducatorProfile();

        $taughtDisciplines = $this->_queryBus->dispatch(
            new ListStudentDisciplineEnrolmentsQuery(
                studentRegistrationKey: $student->getKey(),
                disciplineKey: Optional::empty(),
            ),
        );

        return $this->_inertia->render('Educator/Students/ManageOverview', [
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
