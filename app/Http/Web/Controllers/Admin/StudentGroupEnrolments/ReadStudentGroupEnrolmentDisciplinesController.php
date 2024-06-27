<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Admin\StudentGroupEnrolments;

use App\ApplicationServices\StudentDisciplineEnrolments\ListGroupedByDisciplineFilteredPaginated\ListStudentDisciplineEnrolmentsGroupedByDisciplineFilteredPaginatedQuery;
use App\Core\Contracts\Cqrs\IQueryBus;
use App\Core\Models\{StudentDisciplineEnrolment, StudentGroupEnrolment};
use App\Core\Optional;
use App\Http\Web\Controllers\Controller;
use App\Http\Web\ViewModels\Admin\StudentDisciplineEnrolmentViewModel;
use App\Http\Web\ViewModels\Assemblers\Admin\StudentDisciplineEnrolmentViewModelAssembler;
use App\Http\Web\ViewModels\StudentEnrolmentDetailsViewModel;
use Illuminate\Http\Request;
use Illuminate\Support\{Enumerable, ItemNotFoundException};
use Inertia\{Response as InertiaResponse, ResponseFactory};
use InvalidArgumentException;
use RuntimeException;
use Spatie\RouteAttributes\Attributes\Get;

final readonly class ReadStudentGroupEnrolmentDisciplinesController extends
    Controller
{
    public function __construct(
        private ResponseFactory $_inertia,
        private IQueryBus $_queryBus,
        private StudentDisciplineEnrolmentViewModelAssembler $_disciplineEnrolmentViewModelAssembler,
    ) {
    }

    /**
     * @throws RuntimeException
     */
    #[
        Get(
            '/Admin/StudentEnrolments/{enrolment}/Disciplines',
            name: 'admin.studentGroupEnrolments.read.disciplines',
        ),
    ]
    public function __invoke(
        Request $request,
        StudentGroupEnrolment $enrolment,
    ): InertiaResponse {
        $disciplineEnrolmentGroups = $this->_queryBus->dispatch(
            new ListStudentDisciplineEnrolmentsGroupedByDisciplineFilteredPaginatedQuery(
                page: $request->integer('page'),
                pageSize: $request->integer('pageSize'),
                studentGroupEnrolmentKey: Optional::of($enrolment->getKey()),
            ),
        );

        $disciplineEnrolmentGroups->setCollection(
            $disciplineEnrolmentGroups
                ->getCollection()
                ->map(fn (array $group) => $this->buildGroupViewModel($group)),
        );

        $disciplineEnrolmentGroups = $disciplineEnrolmentGroups->withQueryString();

        return $this->_inertia->render(
            'Admin/StudentEnrolments/ReadDisciplines',
            [
                'enrolment' => StudentEnrolmentDetailsViewModel::fromModel(
                    $enrolment,
                ),
                'disciplines' => $disciplineEnrolmentGroups,
            ],
        );
    }

    /**
     * @param array{
     *       0: string,
     *       1: Enumerable<int, StudentDisciplineEnrolment>
     *   } $group
     * @throws ItemNotFoundException
     * @throws InvalidArgumentException
     */
    protected function buildGroupViewModel(
        array $group,
    ): StudentDisciplineEnrolmentViewModel {
        return $this->_disciplineEnrolmentViewModelAssembler->assemble(
            $group[1],
        );
    }
}
