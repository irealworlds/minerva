<?php

declare(strict_types=1);

namespace App\Http\Api\Endpoints\Student\Disciplines;

use App\ApplicationServices\Disciplines\ListByStudentGroupEnrolmentFilteredPaginated\ListDisciplinesByStudentGroupEnrolmentFilteredPaginatedQuery;
use App\Core\Contracts\Cqrs\IQueryBus;
use App\Core\Models\{Discipline, StudentGroupEnrolment};
use App\Http\Api\Assemblers\Dtos\Student\DisciplineDtoAssembler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use Spatie\RouteAttributes\Attributes\Get;

final readonly class ListByStudentGroupEnrolmentEndpoint
{
    public function __construct(
        private IQueryBus $_queryBus,
        private DisciplineDtoAssembler $_disciplineDtoAssembler,
    ) {
    }

    /**
     * @throws ValidationException
     * @throws InvalidArgumentException
     */
    #[
        Get(
            '/Student/StudentGroupEnrolments/{enrolment}/Disciplines',
            name: 'api.student.studentGroupEnrolments.disciplines.index',
        ),
    ]
    public function __invoke(
        ListByStudentGroupEnrolmentEndpointRequest $request,
        StudentGroupEnrolment $enrolment,
    ): JsonResponse {
        $disciplines = $this->_queryBus->dispatch(
            new ListDisciplinesByStudentGroupEnrolmentFilteredPaginatedQuery(
                page: $request->integer('page'),
                pageSize: $request->integer('pageSize'),
                studentGroupEnrolmentKey: $enrolment->getKey(),
                searchQuery: $request->optionalString('searchQuery', false),
            ),
        );

        $disciplines->setCollection(
            $disciplines
                ->getCollection()
                ->map(
                    fn (
                        Discipline $discipline,
                    ) => $this->_disciplineDtoAssembler->assemble($discipline),
                ),
        );

        return new JsonResponse($disciplines);
    }
}
