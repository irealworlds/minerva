<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Admin\Institutions\Management;

use App\ApplicationServices\StudentGroups\TreeByInstitution\ListStudentGroupsByInstitutionQuery;
use App\Core\Contracts\Cqrs\IQueryBus;
use App\Core\Models\{Institution, StudentGroup};
use App\Http\Web\Controllers\Controller;
use App\Http\Web\ViewModels\{StudentGroupTreeViewModel};
use App\Http\Web\ViewModels\{StudentGroupTreeNodeViewModel};
use App\Http\Web\ViewModels\Assemblers\InstitutionViewModelAssembler;
use Inertia\{Response as InertiaResponse, ResponseFactory};
use Spatie\RouteAttributes\Attributes\Get;

final readonly class ManageInstitutionGroupsController extends Controller
{
    public function __construct(
        private IQueryBus $_queryBus,
        private InstitutionViewModelAssembler $_institutionViewModelAssembler,
        private ResponseFactory $_inertia,
    ) {
    }

    #[
        Get(
            '/Admin/Institutions/Manage/{institution}/Groups',
            name: 'admin.institutions.show.groups',
        ),
    ]
    public function __invoke(Institution $institution): InertiaResponse
    {
        // Render the management view
        return $this->_inertia->render(
            'Admin/Institutions/ManageInstitutionGroups',
            [
                'institution' => fn() => $this->_institutionViewModelAssembler->assemble(
                    $institution,
                ),
                'groups' => function () use ($institution) {
                    $groups = $this->_queryBus->dispatch(
                        new ListStudentGroupsByInstitutionQuery(
                            institution: $institution,
                        ),
                    );

                    return new StudentGroupTreeViewModel(
                        items: $groups->map(
                            static fn(
                                StudentGroup $studentGroup,
                            ) => StudentGroupTreeNodeViewModel::fromModel(
                                $studentGroup,
                            ),
                        ),
                    );
                },
            ],
        );
    }
}
