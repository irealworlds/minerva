<?php

declare(strict_types=1);

namespace App\Http\Api\Endpoints\Student\StudentDisciplineEnrolments;

use App\ApplicationServices\StudentDisciplineEnrolments\ListFilteredPaginated\ListStudentDisciplineEnrolmentsFilteredPaginatedQuery;
use App\ApplicationServices\StudentGroups\FindByRouteKey\FindStudentGroupByRouteKeyQuery;
use App\Core\Contracts\Cqrs\IQueryBus;
use App\Core\Models\{Identity, StudentDisciplineEnrolment, StudentRegistration};
use App\Core\Optional;
use App\Http\Api\Assemblers\Dtos\Student\StudentDisciplineEnrolmentDtoAssembler;
use App\Http\Api\Endpoints\Endpoint;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Factory as AuthManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use Spatie\RouteAttributes\Attributes\Get;

final readonly class ListEndpoint extends Endpoint
{
    public function __construct(
        private IQueryBus $_queryBus,
        private AuthManager $_authManager,
        private StudentDisciplineEnrolmentDtoAssembler $_studentDisciplineEnrolmentDtoAssembler,
    ) {
    }

    /**
     * @throws AuthorizationException
     * @throws AuthenticationException
     * @throws InvalidArgumentException
     * @throws ValidationException
     */
    #[
        Get(
            '/Student/StudentDisciplineEnrolments',
            name: 'api.student.studentDisciplineEnrolments.index',
        ),
    ]
    public function __invoke(ListEndpointRequest $request): JsonResponse
    {
        if ($request->filled('studentGroupKey')) {
            $studentGroup = $this->_queryBus->dispatch(
                new FindStudentGroupByRouteKeyQuery(
                    routeKey: $request->string('studentGroupKey')->toString(),
                ),
            );
            if (empty($studentGroup)) {
                throw ValidationException::withMessages([
                    'studentGroupKey' => __('validation.exists', [
                        'attribute' => 'student group',
                    ]),
                ]);
            }

            $studentGroupKey = Optional::of($studentGroup->getKey());
        } else {
            $studentGroupKey = Optional::empty();
        }

        $disciplineEnrolments = $this->_queryBus->dispatch(
            new ListStudentDisciplineEnrolmentsFilteredPaginatedQuery(
                page: $request->integer('page'),
                pageSize: $request->integer('pageSize'),
                disciplineKey: Optional::empty(),
                educatorKey: Optional::empty(),
                studentGroupKey: $studentGroupKey,
                studentRegistrationKey: Optional::of(
                    $this->getAuthenticatedStudentRegistration()->getKey(),
                ),
            ),
        );

        $disciplineEnrolments->setCollection(
            $disciplineEnrolments
                ->getCollection()
                ->map(
                    fn (
                        StudentDisciplineEnrolment $disciplineEnrolment,
                    ) => $this->_studentDisciplineEnrolmentDtoAssembler->assemble(
                        $disciplineEnrolment,
                    ),
                ),
        );

        return new JsonResponse($disciplineEnrolments);
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
