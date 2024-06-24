<?php

declare(strict_types=1);

namespace App\Http\Api\Endpoints\Admin\StudentGroups;

use App\ApplicationServices\Institutions\FindById\FindInstitutionsByRouteKeysQuery;
use App\ApplicationServices\StudentGroups\ListFilteredPaginated\ListFilteredPaginatedStudentGroupsQuery;
use App\Core\Contracts\Cqrs\IQueryBus;
use App\Core\Models\{Institution, StudentGroup};
use App\Core\Optional;
use App\Http\Api\Endpoints\Endpoint;
use App\Http\Web\ViewModels\StudentGroupViewModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use Spatie\RouteAttributes\Attributes\Get;

final readonly class ListEndpoint extends Endpoint
{
    public function __construct(private IQueryBus $_queryBus)
    {
    }

    /**
     * Handle the incoming request.
     *
     * @throws ValidationException
     * @throws InvalidArgumentException
     */
    #[Get('/Admin/StudentGroups', name: 'api.admin.student_groups.index')]
    public function __invoke(ListEndpointRequest $request): JsonResponse
    {
        // Parse the parent type from the request
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

        // Parse the descendant of institution IDs filter from the request
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
                        static fn (
                            Institution $institution,
                        ) => $institution->getKey(),
                    )
                    ->toArray(),
            );
        } else {
            $descendantOfInstitutionIdsFilter = Optional::empty();
        }

        // Fetch the groups via a query
        $studentGroups = $this->_queryBus->dispatch(
            new ListFilteredPaginatedStudentGroupsQuery(
                page: $request->integer('page', 1),
                pageSize: $request->integer('pageSize', 10),
                parentType: $parentType,
                parentId: $request->optionalString('parentId'),
                searchQuery: $request->optionalString('search', false),
                descendantOfInstitutionIds: $descendantOfInstitutionIdsFilter,
            ),
        );

        // Map results to view models
        $studentGroups->setCollection(
            $studentGroups
                ->getCollection()
                ->map(
                    static fn (
                        StudentGroup $group,
                    ) => StudentGroupViewModel::fromModel($group),
                ),
        );

        $studentGroups = $studentGroups->withQueryString();

        // Render the view
        return new JsonResponse([
            'results' => $studentGroups,
        ]);
    }
}
