<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Institutions\Management;

use App\Core\Models\Institution;
use App\Http\Web\Controllers\Controller;
use App\Http\Web\ViewModels\Assemblers\InstitutionViewModelAssembler;
use Inertia\{Inertia, Response as InertiaResponse};
use RuntimeException;
use Spatie\RouteAttributes\Attributes\Get;

final readonly class ManageInstitutionPublicProfileController extends Controller
{
    public function __construct(
        private InstitutionViewModelAssembler $_institutionViewModelAssembler,
    ) {
    }

    /**
     * @throws RuntimeException
     */
    #[
        Get(
            '/Institutions/Manage/{institution}/General',
            name: 'institutions.show.general',
        ),
    ]
    public function __invoke(Institution $institution): InertiaResponse
    {
        // Render the management view
        return Inertia::render('Institutions/ManageInstitutionDetails', [
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
        ]);
    }
}
