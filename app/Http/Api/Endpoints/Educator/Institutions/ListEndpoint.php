<?php

declare(strict_types=1);

namespace App\Http\Api\Endpoints\Educator\Institutions;

use App\ApplicationServices\Institutions\ListFilteredPaginated\ListFilteredPaginatedInstitutionsQuery;
use App\Core\Contracts\Cqrs\IQueryBus;
use App\Core\Models\Institution;
use App\Http\Api\Assemblers\Dtos\Educator\InstitutionDtoAssembler;
use App\Http\Api\Endpoints\Admin\Institutions\ListEndpointRequest;
use App\Http\Api\Endpoints\Endpoint;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use Spatie\RouteAttributes\Attributes\Get;

final readonly class ListEndpoint extends Endpoint
{
    public function __construct(
        private IQueryBus $_queryBus,
        private InstitutionDtoAssembler $_institutionDtoAssembler,
    ) {
    }

    /**
     * @throws InvalidArgumentException
     * @throws ValidationException
     */
    #[Get('/Educator/Institutions', name: 'api.educator.institutions.index')]
    public function __invoke(ListEndpointRequest $request): JsonResponse
    {
        // Fetch the institutions via a query
        $institutions = $this->_queryBus->dispatch(
            new ListFilteredPaginatedInstitutionsQuery(
                page: $request->integer('page', 1),
                pageSize: $request->integer('pageSize', 10),
                parentId: $request->optionalString('parentId'),
                searchQuery: $request->optionalString('searchQuery', false),
            ),
        );

        // Map results to view models
        $institutions->setCollection(
            $institutions
                ->getCollection()
                ->map(
                    fn(
                        Institution $institution,
                    ) => $this->_institutionDtoAssembler->assemble(
                        $institution,
                    ),
                ),
        );

        $institutions = $institutions->withQueryString();

        // Render the view
        return new JsonResponse($institutions);
    }
}
