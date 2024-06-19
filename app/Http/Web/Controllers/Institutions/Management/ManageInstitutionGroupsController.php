<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Institutions\Management;

use App\ApplicationServices\StudentGroups\TreeByInstitution\ListStudentGroupsByInstitutionQuery;
use App\Core\Contracts\Cqrs\IQueryBus;
use App\Core\Models\{Institution, StudentGroup};
use App\Http\Web\Controllers\Controller;
use App\Http\Web\ViewModels\{StudentGroupTreeViewModel};
use App\Http\Web\ViewModels\{
    InstitutionViewModel,
    StudentGroupTreeNodeViewModel,
};
use Inertia\{Inertia, Response as InertiaResponse};
use RuntimeException;
use Spatie\RouteAttributes\Attributes\Get;

final readonly class ManageInstitutionGroupsController extends Controller
{
    public function __construct(private IQueryBus $_queryBus)
    {
    }

    /**
     * @throws RuntimeException
     */
    #[
        Get(
            '/Institutions/Manage/{institution}/Groups',
            name: 'institutions.show.groups',
        ),
    ]
    public function __invoke(Institution $institution): InertiaResponse
    {
        // Render the management view
        return Inertia::render('Institutions/ManageInstitutionGroups', [
            'institution' => static fn () => InstitutionViewModel::fromModel(
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
                        static fn (
                            StudentGroup $studentGroup,
                        ) => StudentGroupTreeNodeViewModel::fromModel(
                            $studentGroup,
                        ),
                    ),
                );
            },
        ]);
    }
}
