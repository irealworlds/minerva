<?php

namespace App\Http\Controllers\Institutions;

use App\Core\Models\Institution;
use App\Http\Controllers\Controller;
use App\Http\ViewModels\ViewModels\InstitutionViewModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Spatie\RouteAttributes\Attributes\Get;

/** @noinspection PhpMultipleClassesDeclarationsInOneFile */
enum InstitutionReadPageTab: string {
    case General = "General";
    case GroupStructure = "Groups";
    case Educators = "Educators";
    case StudentEnrollments = "Enrollments";
}

final class InstitutionReadController extends Controller
{
    protected const DefaultTab = InstitutionReadPageTab::General;

    function __construct(
        private readonly Redirector $_redirector
    ) {
    }

    #[Get("/Institutions/Manage/{institution}/{tab?}", name: "institutions.show")]
    public function __invoke(Institution $institution, InstitutionReadPageTab|null $tab = null): InertiaResponse|RedirectResponse
    {
        // Redirect to the default tab if no tab is specified
        if ($tab === null) {
            return $this->_redirector->action(InstitutionReadController::class, [
                'institution' => $institution,
                'tab' => InstitutionReadController::DefaultTab
            ]);
        }

        // Render the management view
        return Inertia::render("Institutions/Manage", [
            "institution" => fn() => InstitutionViewModel::fromModel($institution),
            "activeTab" => $tab
        ]);
    }
}
