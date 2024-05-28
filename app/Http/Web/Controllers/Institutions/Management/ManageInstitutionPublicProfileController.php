<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Institutions\Management;

use App\Core\Models\Institution;
use App\Http\Web\Controllers\Controller;
use App\Http\Web\ViewModels\InstitutionViewModel;
use Inertia\{Inertia, Response as InertiaResponse};
use RuntimeException;
use Spatie\RouteAttributes\Attributes\Get;

final readonly class ManageInstitutionPublicProfileController extends Controller
{
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
            'institution' => static fn () => InstitutionViewModel::fromModel(
                $institution,
            ),
        ]);
    }
}
