<?php

declare(strict_types=1);

namespace App\Http\Api\Endpoints\Disciplines;

use App\ApplicationServices\Disciplines\ListFilteredPaginated\ListFilteredPaginatedDisciplinesQuery;
use App\Core\Contracts\Cqrs\IQueryBus;
use App\Core\Models\{Discipline, Institution};
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

        // Fetch the disciplines via a query
        $disciplines = $this->_queryBus->dispatch(
            new ListFilteredPaginatedDisciplinesQuery(
                page: $request->integer('page', 1),
                pageSize: $request->integer('pageSize', 10),
                searchQuery: $request->optionalString('search', false),
                notAssociatedToInstitutionIds: $notAssociatedToInstitutionIdsFilter,
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
