<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Student\Enrolments;

use App\ApplicationServices\StudentGroupEnrolments\ListFilteredPaginatedByStudent\ListStudentGroupEnrolmentsByStudentFilteredPaginatedQuery;
use App\Core\Contracts\Cqrs\IQueryBus;
use App\Core\Models\Identity;
use App\Core\Models\StudentGroupEnrolment;
use App\Core\Models\StudentRegistration;
use App\Http\Web\ViewModels\Assemblers\Student\StudentGroupEnrolmentViewModelAssembler;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Factory as AuthManager;
use Illuminate\Http\Request;
use Inertia\Response as InertiaResponse;
use Inertia\ResponseFactory;
use Spatie\RouteAttributes\Attributes\Get;

final readonly class ListController
{
    function __construct(
        private ResponseFactory $_inertia,
        private IQueryBus $_queryBus,
        private AuthManager $_authManager,
        private StudentGroupEnrolmentViewModelAssembler $_studentGroupEnrolmentViewModelAssembler,
    ) {
    }

    /**
     * @throws AuthorizationException
     * @throws AuthenticationException
     */
    #[Get('/Student/Enrolments/List', 'student.enrolments.list')]
    public function __invoke(Request $request): InertiaResponse
    {
        $studentRegistration = $this->getAuthenticatedStudentRegistration();

        $enrolments = $this->_queryBus->dispatch(
            new ListStudentGroupEnrolmentsByStudentFilteredPaginatedQuery(
                page: $request->integer('page'),
                pageSize: $request->integer('pageSize'),
                studentRegistrationKey: $studentRegistration->getKey(),
            ),
        );

        $enrolments->setCollection(
            $enrolments
                ->getCollection()
                ->map(
                    fn(
                        StudentGroupEnrolment $enrolment,
                    ) => $this->_studentGroupEnrolmentViewModelAssembler->assemble(
                        $enrolment,
                    ),
                ),
        );

        return $this->_inertia->render('Student/Enrolments/List', [
            'enrolments' => $enrolments,
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
