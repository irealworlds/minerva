<?php

declare(strict_types=1);

namespace App\Http\Api\Endpoints\Educator\StudentGroups;

use App\ApplicationServices\Institutions\FindById\FindInstitutionsByRouteKeysQuery;
use App\ApplicationServices\StudentGroups\ListFilteredPaginated\ListFilteredPaginatedStudentGroupsQuery;
use App\Core\Contracts\Cqrs\IQueryBus;
use App\Core\Models\{Educator, Identity, Institution, StudentGroup};
use App\Core\Optional;
use App\Http\Api\Assemblers\Dtos\Educator\StudentGroupDtoAssembler;
use App\Http\Api\Endpoints\Admin\StudentGroups\ListEndpointRequest;
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
        private StudentGroupDtoAssembler $_studentGroupDtoAssembler,
    ) {
    }

    /**
     * Handle the incoming request.
     *
     * @throws AuthenticationException
     * @throws AuthorizationException
     * @throws ValidationException
     * @throws InvalidArgumentException
     */
    #[Get('/Educator/StudentGroups', name: 'api.educator.studentGroups.index')]
    public function __invoke(ListEndpointRequest $request): JsonResponse
    {
        // Fetch the groups via a query
        $studentGroups = $this->_queryBus->dispatch(
            new ListFilteredPaginatedStudentGroupsQuery(
                page: $request->integer('page', 1),
                pageSize: $request->integer('pageSize', 10),
                parentType: $this->extractParentType($request),
                parentId: $request->optionalString('parentId'),
                searchQuery: $request->optionalString('searchQuery', false),
                descendantOfInstitutionIds: $this->extractDescendantOfInstitutionIds(
                    $request,
                ),
                associatedEducatorIds: Optional::of([
                    $this->getAuthenticatedEducatorProfile()->getKey(),
                ]),
            ),
        );

        // Map results to view models
        $studentGroups->setCollection(
            $studentGroups
                ->getCollection()
                ->map(
                    fn(
                        StudentGroup $group,
                    ) => $this->_studentGroupDtoAssembler->assemble($group),
                ),
        );

        $studentGroups = $studentGroups->withQueryString();

        // Render the view
        return new JsonResponse($studentGroups);
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
     * @throws ValidationException
     * @return Optional<string|null>
     */
    public function extractParentType(ListEndpointRequest $request): Optional
    {
        if ($request->optionalString('parentType')->hasValue()) {
            if (
                !strcasecmp(
                    $request->string('parentType')->toString(),
                    'institution',
                )
            ) {
                $parentType = Optional::of(Institution::class);
            } elseif (
                !strcasecmp(
                    $request->string('parentType')->toString(),
                    'studentGroup',
                )
            ) {
                $parentType = Optional::of(StudentGroup::class);
            } else {
                throw ValidationException::withMessages([
                    'parentType' => __('validation.in', [
                        'attribute' => 'parent type',
                    ]),
                ]);
            }
        } else {
            $parentType = Optional::empty();
        }
        return $parentType;
    }

    /**
     * @throws ValidationException
     * @return Optional<mixed[]>
     */
    public function extractDescendantOfInstitutionIds(
        ListEndpointRequest $request,
    ): Optional {
        if (
            $request->optionalString('descendantOfInstitutionIds')->hasValue()
        ) {
            $institutionRouteKeys = explode(
                ',',
                $request->string('descendantOfInstitutionIds')->toString(),
            );
            $descendantOfInstitutionIdsFilter = Optional::of(
                $this->_queryBus
                    ->dispatch(
                        new FindInstitutionsByRouteKeysQuery(
                            ...$institutionRouteKeys,
                        ),
                    )
                    ->map(
                        static fn(
                            Institution $institution,
                        ) => $institution->getKey(),
                    )
                    ->toArray(),
            );
        } else {
            $descendantOfInstitutionIdsFilter = Optional::empty();
        }
        return $descendantOfInstitutionIdsFilter;
    }
}
