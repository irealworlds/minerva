<?php

declare(strict_types=1);

namespace App\Http\Api\Endpoints\Educator\StudentDisciplineEnrolments;

use App\ApplicationServices\StudentDisciplineEnrolments\ListFilteredPaginated\ListStudentDisciplineEnrolmentsFilteredPaginatedQuery;
use App\Core\Contracts\Cqrs\IQueryBus;
use App\Core\Models\Educator;
use App\Core\Models\Identity;
use App\Core\Models\StudentDisciplineEnrolment;
use App\Core\Optional;
use App\Http\Api\Assemblers\Dtos\Educator\StudentDisciplineEnrolmentDtoAssembler;
use App\Http\Web\Controllers\Controller;
use Codestage\Authorization\Attributes\Authorize;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Factory as AuthManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use Spatie\RouteAttributes\Attributes\Get;

final readonly class ListEndpoint extends Controller
{
    function __construct(
        private IQueryBus $_queryBus,
        private AuthManager $_authManager,
        private StudentDisciplineEnrolmentDtoAssembler $_disciplineEnrolmentDtoAssembler,
    ) {
    }

    /**
     * @throws AuthenticationException
     * @throws AuthorizationException
     * @throws ValidationException
     * @throws InvalidArgumentException
     */
    #[
        Get(
            '/Educator/StudentDisciplineEnrolments',
            name: 'api.educator.studentDisciplineEnrolments.index',
        ),
    ]
    #[Authorize]
    public function __invoke(ListEndpointRequest $request): JsonResponse
    {
        $educatorKey = $this->getAuthenticatedEducatorProfile()->getKey();

        $disciplineEnrolments = $this->_queryBus->dispatch(
            new ListStudentDisciplineEnrolmentsFilteredPaginatedQuery(
                page: $request->integer('page'),
                pageSize: $request->integer('pageSize'),
                disciplineKey: $request->optionalString('disciplineKey', false),
                educatorKey: Optional::of($educatorKey),
                studentGroupKey: $request->optionalString(
                    'studentGroupKey',
                    false,
                ),
                studentRegistrationKey: $request->optionalString(
                    'studentRegistrationKey',
                    false,
                ),
            ),
        );

        $disciplineEnrolments->setCollection(
            $disciplineEnrolments
                ->getCollection()
                ->map(
                    fn(
                        StudentDisciplineEnrolment $enrolment,
                    ) => $this->_disciplineEnrolmentDtoAssembler->assemble(
                        $enrolment,
                    ),
                ),
        );

        return new JsonResponse($disciplineEnrolments);
    }

    /**
     * @throws AuthenticationException
     * @throws AuthorizationException
     */
    protected function getAuthenticatedEducatorProfile(): Educator
    {
        /** @var Identity|null $identity */
        $identity = $this->_authManager->guard()->user();

        if (empty($identity)) {
            throw new AuthenticationException();
        }

        $educator = $identity->educatorProfile;

        if (empty($educator)) {
            throw new AuthorizationException();
        }

        return $educator;
    }
}
