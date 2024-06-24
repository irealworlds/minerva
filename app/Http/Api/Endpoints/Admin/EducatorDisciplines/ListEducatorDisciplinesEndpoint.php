<?php

declare(strict_types=1);

namespace App\Http\Api\Endpoints\Admin\EducatorDisciplines;

use App\ApplicationServices\Educators\ListStudentGroupDisciplinesFilteredPaginated\ListStudentGroupDisciplinesForEducatorFilteredPaginatedQuery;
use App\Core\Contracts\Cqrs\IQueryBus;
use App\Core\Models\{Educator, StudentGroupDisciplineEducator};
use App\Core\Optional;
use App\Http\Api\Assemblers\Dtos\Admin\EducatorTaughtDisciplineDtoAssembler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use Spatie\RouteAttributes\Attributes\Get;

final readonly class ListEducatorDisciplinesEndpoint
{
    public function __construct(
        private IQueryBus $_queryBus,
        private EducatorTaughtDisciplineDtoAssembler $_dtoAssembler,
    ) {
    }

    /**
     * @throws ValidationException
     * @throws InvalidArgumentException
     */
    #[
        Get(
            '/Admin/Educators/{educator}/Disciplines',
            name: 'api.admin.educators.disciplines.index',
        ),
    ]
    public function __invoke(
        ListEducatorDisciplinesRequest $request,
        Educator $educator,
    ): JsonResponse {
        // Get the institution key from the educator
        if ($request->has('institutionKey')) {
            $institutionKey = $request->optionalString('institutionKey');
        } else {
            $institutionKey = Optional::empty();
        }

        // Fetch the disciplines
        $disciplines = $this->_queryBus->dispatch(
            new ListStudentGroupDisciplinesForEducatorFilteredPaginatedQuery(
                educatorKey: $educator->getKey(),
                page: $request->integer('page'),
                pageSize: $request->integer('pageSize'),
                institutionKey: $institutionKey,
            ),
        );

        // Map the disciplines to DTOs
        $disciplines->setCollection(
            $disciplines
                ->getCollection()
                ->map(
                    fn (
                        StudentGroupDisciplineEducator $association,
                    ) => $this->_dtoAssembler->assemble($association),
                ),
        );

        // Return the paginated list of disciplines
        return new JsonResponse($disciplines);
    }
}
