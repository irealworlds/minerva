<?php

namespace App\Http\Endpoints\Institutions;

use App\ApplicationServices\Institutions\ListFilteredPaginated\ListFilteredPaginatedInstitutionsQuery;
use App\Core\Contracts\Cqrs\IQueryBus;
use App\Core\Models\Institution;
use App\Http\Endpoints\Endpoint;
use App\Http\Requests\Institutions\InstitutionListRequest;
use App\Http\ViewModels\ViewModels\InstitutionViewModel;
use Illuminate\Http\JsonResponse;
use Spatie\RouteAttributes\Attributes\Get;

final class ListEndpoint extends Endpoint
{
    function __construct(
        private readonly IQueryBus $_queryBus
    ) {
    }

    #[Get("/Institutions", name: "api.institutions.index")]
    public function __invoke(InstitutionListRequest $request): JsonResponse
    {
        // Fetch the institutions via a query
        $institutions = $this->_queryBus->dispatch(new ListFilteredPaginatedInstitutionsQuery(
            page: $request->integer("page", 1),
            pageSize: $request->integer("pageSize", 10),
            parentId: $request->optional("parentId"),
            searchQuery: $request->optional("search")
        ));

        // Map results to view models
        $institutions->setCollection(
            $institutions->getCollection()
                ->map(fn(Institution $institution) => InstitutionViewModel::fromModel($institution))
        );

        $institutions = $institutions->withQueryString();

        // Render the view
        return new JsonResponse([
            "institutions" => $institutions,
            "filters" => [
                "search" => $request->search
            ]
        ]);
    }
}
