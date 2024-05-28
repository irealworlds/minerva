<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Institutions;

use App\Core\Models\{Institution};
use App\Http\Web\Controllers\Controller;
use App\Http\Web\Controllers\Institutions\Management\ManageInstitutionPublicProfileController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Spatie\RouteAttributes\Attributes\Get;

final readonly class InstitutionReadController extends Controller
{
    public function __construct(private Redirector $_redirector)
    {
    }

    #[Get('/Institutions/Manage/{institution}', name: 'institutions.show')]
    public function __invoke(Institution $institution): RedirectResponse
    {
        return $this->_redirector->action(
            ManageInstitutionPublicProfileController::class,
            ['institution' => $institution->getRouteKey()],
        );
    }
}
