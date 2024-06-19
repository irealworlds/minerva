<?php

declare(strict_types=1);

namespace App\Http\Api\Endpoints\StudentRegistrations;

use App\ApplicationServices\StudentRegistrations\ListFilteredPaginated\ListFilteredPaginatedStudentRegistrationsQuery;
use App\Core\Contracts\Cqrs\IQueryBus;
use App\Core\Models\StudentRegistration;
use App\Http\Api\Dtos\StudentRegistrationDto;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use Spatie\RouteAttributes\Attributes\Get;

final readonly class ListEndpoint
{
    public function __construct(private IQueryBus $_queryBus)
    {
    }

    /**
     * @throws InvalidArgumentException
     * @throws ValidationException
     */
    #[Get('/StudentRegistrations', 'api.student_registrations.index')]
    public function __invoke(ListEndpointRequest $request): JsonResponse
    {
        // Fetch the registrations via a query
        $registrations = $this->_queryBus->dispatch(
            new ListFilteredPaginatedStudentRegistrationsQuery(
                page: $request->integer('page', 1),
                pageSize: $request->integer('pageSize', 10),
                searchQuery: $request->optionalString('search', false),
            ),
        );

        // Map the results to DTOs
        $registrations->setCollection(
            $registrations
                ->getCollection()
                ->map(
                    static fn (
                        StudentRegistration $registration,
                    ) => StudentRegistrationDto::fromModel($registration),
                ),
        );

        // Preserve query strings for links
        $registrations = $registrations->withQueryString();

        // Return a JSON response
        return new JsonResponse([
            'results' => $registrations,
        ]);
    }
}
