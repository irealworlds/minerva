<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Institutions;

use App\ApplicationServices\StudentGroups\TreeByInstitution\ListStudentGroupsByInstitutionQuery;
use App\Core\Contracts\Cqrs\IQueryBus;
use App\Core\Models\{
    Institution,
    StudentGroup};
use App\Http\Web\Controllers\Controller;
use App\Http\Web\ViewModels\{
    StudentGroupTreeNodeViewModel};
use App\Http\Web\ViewModels\{
    InstitutionViewModel,
    StudentGroupTreeViewModel};
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Session\Store;
use Inertia\{
    Inertia,
    Response as InertiaResponse};
use RuntimeException;
use Spatie\RouteAttributes\Attributes\Get;

/** @noinspection PhpMultipleClassesDeclarationsInOneFile */
enum InstitutionReadPageTab: string
{
    case General = 'General';
    case GroupStructure = 'Groups';
    case Educators = 'Educators';
    case StudentEnrollments = 'Enrollments';
}

final readonly class InstitutionReadController extends Controller
{
    protected const DefaultTab = InstitutionReadPageTab::General;

    public function __construct(
        private Redirector $_redirector,
        private IQueryBus $_queryBus,
        private Store $_session
    ) {
    }

    /**
     * @throws RuntimeException
     */
    #[Get('/Institutions/Manage/{institution}/{tab?}', name: 'institutions.show')]
    public function __invoke(Institution $institution, InstitutionReadPageTab|null $tab = null): InertiaResponse|RedirectResponse
    {
        // Redirect to the default tab if no tab is specified
        if ($tab === null) {
            $this->_session->reflash();
            return $this->_redirector->action(InstitutionReadController::class, [
                'institution' => $institution,
                'tab' => InstitutionReadController::DefaultTab
            ]);
        }

        // Render the management view
        return Inertia::render('Institutions/Manage', [
            'institution' => static fn () => InstitutionViewModel::fromModel($institution),
            'activeTab' => $tab,
            'groups' => Inertia::lazy(function () use ($institution) {
                $groups = $this->_queryBus->dispatch(new ListStudentGroupsByInstitutionQuery(institution: $institution));

                return new StudentGroupTreeViewModel(
                    items: $groups->map(static fn (StudentGroup $studentGroup) => StudentGroupTreeNodeViewModel::fromModel($studentGroup))
                );
            })
        ]);
    }
}
