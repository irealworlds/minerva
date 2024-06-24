<?php

declare(strict_types=1);

namespace App\Http\Api\Endpoints\Admin\Disciplines;

use App\ApplicationServices\StudentGroupDisciplines\ListPaginatedFiltered\ListPaginatedFilteredStudentGroupDisciplinesQuery;
use App\Core\Contracts\Cqrs\IQueryBus;
use App\Core\Models\{StudentGroup, StudentGroupDiscipline};
use App\Http\Api\Dtos\Admin\StudentGroupDisciplineDto;
use App\Http\Api\Endpoints\Endpoint;
use Illuminate\Http\JsonResponse;
use InvalidArgumentException;
use Spatie\RouteAttributes\Attributes\{Get, Group};

#[Group('/Admin/StudentGroups/{studentGroup}/Disciplines')]
final readonly class ListForStudentGroupEndpoint extends Endpoint
{
    public function __construct(private IQueryBus $_queryBus)
    {
    }

    /**
     * @throws InvalidArgumentException
     */
    #[Get('/', name: 'api.admin.student_groups.disciplines.index')]
    public function __invoke(
        ListForStudentGroupEndpointRequest $request,
        StudentGroup $studentGroup,
    ): JsonResponse {
        // Fetch the disciplines via a query
        $disciplines = $this->_queryBus->dispatch(
            new ListPaginatedFilteredStudentGroupDisciplinesQuery(
                studentGroupId: $studentGroup->getKey(),
                page: $request->integer('page', 1),
                pageSize: $request->integer('pageSize', 10),
            ),
        );

        // Map results to view models
        $disciplines->setCollection(
            $disciplines
                ->getCollection()
                ->map(
                    static fn (
                        StudentGroupDiscipline $discipline,
                    ) => StudentGroupDisciplineDto::fromModel($discipline),
                ),
        );

        $disciplines = $disciplines->withQueryString();

        // Render the view
        return new JsonResponse([
            'results' => $disciplines,
        ]);
    }
}
