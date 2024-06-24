<?php

declare(strict_types=1);

namespace App\Http\Api\Endpoints\Educator\Disciplines;

use App\ApplicationServices\Disciplines\FindByRouteKey\FindDisciplineByRouteKeyQuery;
use App\ApplicationServices\StudentGroupDisciplineEducators\ListFilteredPaginated\ListStudentGroupDisciplineEducatorsFilteredPaginatedQuery;
use App\ApplicationServices\StudentGroups\FindByRouteKey\FindStudentGroupByRouteKeyQuery;
use App\Core\Contracts\Cqrs\IQueryBus;
use App\Core\Models\Educator;
use App\Core\Models\Identity;
use App\Core\Models\StudentGroupDisciplineEducator;
use App\Core\Optional;
use App\Http\Api\Assemblers\Dtos\Educator\DisciplineDtoAssembler;
use Codestage\Authorization\Attributes\Authorize;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use Spatie\RouteAttributes\Attributes\Get;

final readonly class ListTaughtEndpoint
{
    function __construct(
        private IQueryBus $_queryBus,
        private Factory $_authManager,
        private DisciplineDtoAssembler $_disciplineDtoAssembler,
    ) {
    }

    /**
     * @throws AuthenticationException
     * @throws AuthorizationException
     * @throws ValidationException
     * @throws InvalidArgumentException
     */
    #[Get('/Educator/Disciplines', name: 'api.educator.disciplines.index')]
    #[Authorize]
    public function __invoke(ListTaughtEndpointRequest $request): JsonResponse
    {
        [$studentGroupKey, $disciplineKey] = $this->extractFilterEntities(
            $request,
        );

        $disciplines = $this->_queryBus->dispatch(
            new ListStudentGroupDisciplineEducatorsFilteredPaginatedQuery(
                page: $request->integer('page'),
                pageSize: $request->integer('pageSize'),
                studentGroupKey: $studentGroupKey,
                disciplineKey: $disciplineKey,
                educatorKey: Optional::of(
                    $this->getAuthenticatedEducatorProfile()->getKey(),
                ),
                searchQuery: $request->optionalString('searchQuery', false),
            ),
        );

        $disciplines->setCollection(
            $disciplines
                ->getCollection()
                ->map(
                    fn(
                        StudentGroupDisciplineEducator $disciplineEducator,
                    ) => $this->_disciplineDtoAssembler->assemble(
                        $disciplineEducator->discipline,
                    ),
                ),
        );

        return new JsonResponse($disciplines);
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

    /**
     * @return array{
     *     0: Optional<mixed>,
     *     1: Optional<mixed>,
     * }
     * @throws ValidationException
     */
    public function extractFilterEntities(
        ListTaughtEndpointRequest $request,
    ): array {
        // Get student group key
        if ($request->filled('studentGroupKey')) {
            $studentGroup = $this->_queryBus->dispatch(
                new FindStudentGroupByRouteKeyQuery(
                    routeKey: $request->string('studentGroupKey')->toString(),
                ),
            );
            if (empty($studentGroup)) {
                throw ValidationException::withMessages([
                    'studentGroupKey' => __('validation.exists', [
                        'attribute' => 'studentGroupKey',
                    ]),
                ]);
            }

            $studentGroupKey = Optional::of($studentGroup->getKey());
        } else {
            $studentGroupKey = Optional::empty();
        }

        // Get discipline key
        if ($request->filled('disciplineKey')) {
            $discipline = $this->_queryBus->dispatch(
                new FindDisciplineByRouteKeyQuery(
                    routeKey: $request->string('disciplineKey')->toString(),
                ),
            );
            if (empty($discipline)) {
                throw ValidationException::withMessages([
                    'disciplineKey' => __('validation.exists', [
                        'attribute' => 'disciplineKey',
                    ]),
                ]);
            }

            $disciplineKey = Optional::of($discipline->getKey());
        } else {
            $disciplineKey = Optional::empty();
        }

        return [$studentGroupKey, $disciplineKey];
    }
}
