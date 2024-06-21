<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Admin\Institutions\Management;

use App\Core\Models\Institution;
use App\Http\Web\Controllers\Controller;
use App\Http\Web\ViewModels\Assemblers\InstitutionViewModelAssembler;
use Inertia\{Response as InertiaResponse, ResponseFactory};
use Spatie\RouteAttributes\Attributes\Get;

final readonly class ManageInstitutionPublicProfileController extends Controller
{
    public function __construct(
        private InstitutionViewModelAssembler $_institutionViewModelAssembler,
        private ResponseFactory $_inertia,
    ) {
    }

    #[
        Get(
            '/Admin/Institutions/Manage/{institution}/General',
            name: 'admin.institutions.show.general',
        ),
    ]
    public function __invoke(Institution $institution): InertiaResponse
    {
        // Render the management view
        return $this->_inertia->render(
            'Admin/Institutions/ManageInstitutionDetails',
            [
                'institution' => fn () => $this->_institutionViewModelAssembler->assemble(
                    $institution,
                ),
                'parentInstitutionId' => $institution->parent?->getRouteKey(),
                'parentInstitutionName' => $institution->parent?->name,
                'parentInstitutionPictureUri' => $institution->parent?->getFirstMediaUrl(
                    Institution::EmblemPictureMediaCollection,
                ),
                'parentInstitutionAncestors' => $institution->parent
                    ?->ancestors()
                    ->get()
                    ->map(
                        static fn (Institution $ancestor) => [
                            'id' => $ancestor->getRouteKey(),
                            'name' => $ancestor->name,
                        ],
                    ),
            ],
        );
    }
}
