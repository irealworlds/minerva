<?php

declare(strict_types=1);

namespace App\Http\Api\Endpoints\Disciplines;

use App\ApplicationServices\Disciplines\ListFilteredPaginated\ListFilteredPaginatedDisciplinesQuery;
use App\Core\Contracts\Cqrs\IQueryBus;
use App\Core\Models\{Discipline, Institution, StudentGroup};
use App\Core\Optional;
use App\Http\Api\Dtos\DisciplineDto;
use App\Http\Api\Endpoints\Endpoint;
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
     * @throws InvalidArgumentException
     * @throws ValidationException
     */
    #[Get('/Disciplines', name: 'api.disciplines.index')]
    public function __invoke(ListEndpointRequest $request): JsonResponse
    {
        // Parse the notAssociatedToInstitutionIds filter
        $notAssociatedToInstitutionIdsFilter = Optional::empty();
        if (!empty($request->notAssociatedToInstitutionIds)) {
            $notAssociatedToInstitutionIdsFilter = Optional::of(
                Institution::query()
                    ->whereIn(
                        (new Institution())->getKeyName(),
                        explode(',', $request->notAssociatedToInstitutionIds),
                    )
                    ->get()
                    ->pluck((new Institution())->getKeyName())
                    ->toArray(),
            );
        }
        // Parse the associatedToInstitutionIds filter
        $associatedToInstitutionIdsFilter = Optional::empty();
        if (!empty($request->associatedToInstitutionIds)) {
            $associatedToInstitutionIdsFilter = Optional::of(
                Institution::query()
                    ->whereIn(
                        (new Institution())->getKeyName(),
                        explode(',', $request->associatedToInstitutionIds),
                    )
                    ->get()
                    ->pluck((new Institution())->getKeyName())
                    ->toArray(),
            );
        }
        // Parse the notAssociatedToStudentGroupIds filter
        $notAssociatedToStudentGroupIdsFilter = Optional::empty();
        if (!empty($request->notAssociatedToStudentGroupIds)) {
            $notAssociatedToStudentGroupIdsFilter = Optional::of(
                StudentGroup::query()
                    ->whereIn(
                        (new StudentGroup())->getKeyName(),
                        explode(',', $request->notAssociatedToStudentGroupIds),
                    )
                    ->get()
                    ->pluck((new StudentGroup())->getKeyName())
                    ->toArray(),
            );
        }
        // Parse the associatedToStudentGroupIds filter
        $associatedToStudentGroupIdsFilter = Optional::empty();
        if (!empty($request->associatedToStudentGroupIds)) {
            $associatedToStudentGroupIdsFilter = Optional::of(
                StudentGroup::query()
                    ->whereIn(
                        (new StudentGroup())->getKeyName(),
                        explode(',', $request->associatedToStudentGroupIds),
                    )
                    ->get()
                    ->pluck((new StudentGroup())->getKeyName())
                    ->toArray(),
            );
        }

        // Fetch the disciplines via a query
        $disciplines = $this->_queryBus->dispatch(
            new ListFilteredPaginatedDisciplinesQuery(
                page: $request->integer('page', 1),
                pageSize: $request->integer('pageSize', 10),
                searchQuery: $request->optionalString('search', false),
                associatedToInstitutionIds: $associatedToInstitutionIdsFilter,
                notAssociatedToInstitutionIds: $notAssociatedToInstitutionIdsFilter,
                associatedToStudentGroupIds: $associatedToStudentGroupIdsFilter,
                notAssociatedToStudentGroupIds: $notAssociatedToStudentGroupIdsFilter,
            ),
        );

        // Map results to view models
        $disciplines->setCollection(
            $disciplines
                ->getCollection()
                ->map(
                    static fn (
                        Discipline $discipline,
                    ) => DisciplineDto::fromModel($discipline),
                ),
        );

        $disciplines = $disciplines->withQueryString();

        // Render the view
        return new JsonResponse([
            'disciplines' => $disciplines,
            'filters' => [
                'search' => $request->search,
            ],
        ]);
    }
}
