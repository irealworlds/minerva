<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Student\Enrolments;

use App\ApplicationServices\StudentDisciplineEnrolments\ListFilteredPaginated\ListStudentDisciplineEnrolmentsFilteredPaginatedQuery;
use App\Core\Contracts\Cqrs\IQueryBus;
use App\Core\Models\Identity;
use App\Core\Models\StudentDisciplineEnrolment;
use App\Core\Models\StudentGroupEnrolment;
use App\Core\Models\StudentRegistration;
use App\Core\Optional;
use App\Http\Web\Controllers\Controller;
use App\Http\Web\ViewModels\Assemblers\Student\StudentDisciplineEnrolmentViewModelAssembler;
use App\Http\Web\ViewModels\Assemblers\Student\StudentGroupEnrolmentViewModelAssembler;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Factory as AuthManager;
use Illuminate\Http\Request;
use Inertia\Response as InertiaResponse;
use Inertia\ResponseFactory;
use Spatie\RouteAttributes\Attributes\Get;

final readonly class ReadDisciplinesController extends Controller
{
    function __construct(
        private IQueryBus $_queryBus,
        private AuthManager $_authManager,
        private ResponseFactory $_inertia,
        private StudentGroupEnrolmentViewModelAssembler $_studentGroupEnrolmentViewModelAssembler,
        private StudentDisciplineEnrolmentViewModelAssembler $_studentDisciplineEnrolmentViewModelAssembler,
    ) {
    }

    /**
     * @throws AuthorizationException
     * @throws AuthenticationException
     */
    #[
        Get(
            '/Student/Enrolments/Details/{enrolment}/Disciplines',
            name: 'student.enrolments.read.disciplines',
        ),
    ]
    public function __invoke(
        Request $request,
        StudentGroupEnrolment $enrolment,
    ): InertiaResponse {
        $studentRegistration = $this->getAuthenticatedStudentRegistration();

        $disciplineEnrolments = $this->_queryBus->dispatch(
            new ListStudentDisciplineEnrolmentsFilteredPaginatedQuery(
                page: $request->integer('page'),
                pageSize: $request->integer('pageSize', 6),
                disciplineKey: Optional::empty(),
                educatorKey: Optional::empty(),
                studentGroupKey: Optional::of(
                    $enrolment->studentGroup->getKey(),
                ),
                studentRegistrationKey: Optional::of(
                    $studentRegistration->getKey(),
                ),
            ),
        );

        $disciplineEnrolments->setCollection(
            $disciplineEnrolments
                ->getCollection()
                ->map(
                    fn(
                        StudentDisciplineEnrolment $enrolment,
                    ) => $this->_studentDisciplineEnrolmentViewModelAssembler->assemble(
                        $enrolment,
                    ),
                ),
        );

        $disciplineEnrolments = $disciplineEnrolments->withQueryString();

        return $this->_inertia->render('Student/Enrolments/ReadDisciplines', [
            'enrolment' => $this->_studentGroupEnrolmentViewModelAssembler->assemble(
                $enrolment,
            ),
            'disciplineEnrolments' => $disciplineEnrolments,
        ]);
    }

    /**
     * @throws AuthenticationException
     * @throws AuthorizationException
     */
    protected function getAuthenticatedStudentRegistration(): StudentRegistration
    {
        /** @var Identity|null $identity */
        $identity = $this->_authManager->guard()->user();

        if (empty($identity)) {
            throw new AuthenticationException();
        }

        $studentRegistration = $identity->studentRegistration;

        if (empty($studentRegistration)) {
            throw new AuthorizationException();
        }

        return $studentRegistration;
    }
}
