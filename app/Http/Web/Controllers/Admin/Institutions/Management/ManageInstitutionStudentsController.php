<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Admin\Institutions\Management;

use App\ApplicationServices\StudentGroupEnrolments\ListFilteredPaginatedByInstitution\ListFilteredPaginatedStudentGroupEnrolmentsByInstitutionQuery;
use App\Core\Contracts\Cqrs\IQueryBus;
use App\Core\Models\{Institution, StudentGroupEnrolment};
use App\Core\Optional;
use App\Http\Web\ViewModels\Assemblers\InstitutionViewModelAssembler;
use Illuminate\Http\Request;
use Inertia\{Response as InertiaResponse, ResponseFactory};
use Spatie\RouteAttributes\Attributes\{Get, Group};

#[Group('/Admin/Institutions/Manage/{institution}')]
final readonly class ManageInstitutionStudentsController
{
    public function __construct(
        private ResponseFactory $_inertia,
        private IQueryBus $_queryBus,
        private InstitutionViewModelAssembler $_institutionViewModelAssembler,
    ) {
    }

    #[Get('/Students', name: 'admin.institutions.show.students')]
    public function __invoke(
        Institution $institution,
        Request $request,
    ): InertiaResponse {
        $studentEnrolments = $this->_queryBus->dispatch(
            new ListFilteredPaginatedStudentGroupEnrolmentsByInstitutionQuery(
                institution: $institution,
                page: $request->integer('page', 1),
                pageSize: $request->integer('pageSize', 10),
                searchQuery: Optional::empty(),
            ),
        );
        $studentEnrolments->withQueryString();

        $studentEnrolments->setCollection(
            $studentEnrolments->getCollection()->map(
                static fn (StudentGroupEnrolment $enrolment) => [
                    'id' => $enrolment->id,
                    'name' => $enrolment->studentRegistration->identity->name,
                    'studentRegistrationId' => $enrolment->studentRegistration->getKey(),
                    'studentGroup' => $enrolment->studentGroup->name,
                    'createdAt' => $enrolment->created_at->toIso8601String(),
                ],
            ),
        );

        return $this->_inertia->render(
            'Admin/Institutions/ManageInstitutionStudents',
            [
                'institution' => fn () => $this->_institutionViewModelAssembler->assemble(
                    $institution,
                ),
                'enrolments' => static fn () => $studentEnrolments,
            ],
        );
    }
}
