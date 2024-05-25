<?php

declare(strict_types=1);

namespace App\Http\Controllers\Institutions;

use App\ApplicationServices\StudentGroups\TreeByInstitution\ListStudentGroupsByInstitutionQuery;
use App\Core\Contracts\Cqrs\IQueryBus;
use App\Core\Models\{
    Institution,
    StudentGroup};
use App\Http\Controllers\Controller;
use App\Http\ViewModels\{
    StudentGroupTreeNodeViewModel,
    StudentGroupTreeViewModel};
use App\Http\ViewModels\ViewModels\InstitutionViewModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
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

final class InstitutionReadController extends Controller
{
    protected const DefaultTab = InstitutionReadPageTab::General;

    public function __construct(
        private readonly Redirector $_redirector,
        private readonly IQueryBus $_queryBus
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
