<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Admin\Institutions\Management;

use App\ApplicationServices\Institutions\AddDiscipline\AddDisciplineToInstitutionCommand;
use App\ApplicationServices\Institutions\RemoveDiscipline\RemoveDisciplineFromInstitutionCommand;
use App\Core\Contracts\Cqrs\ICommandBus;
use App\Core\Models\{Discipline, Institution};
use App\Http\Web\Controllers\Controller;
use App\Http\Web\ViewModels\{
    Assemblers\InstitutionViewModelAssembler,
    InstitutionDisciplineViewModel,
};
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Inertia\{Response as InertiaResponse, ResponseFactory};
use ReflectionException;
use Spatie\RouteAttributes\Attributes\{Delete, Get, Group, Post};

#[Group(prefix: '/Admin/Institutions/Manage/{institution}/Disciplines')]
final readonly class ManageInstitutionDisciplinesController extends Controller
{
    public function __construct(
        private ICommandBus $_commandBus,
        private Redirector $_redirector,
        private InstitutionViewModelAssembler $_institutionViewModelAssembler,
        private ResponseFactory $_inertia,
    ) {
    }

    #[Get('/', name: 'admin.institutions.show.disciplines')]
    public function __invoke(Institution $institution): InertiaResponse
    {
        // Render the management view
        return $this->_inertia->render(
            'Admin/Institutions/ManageInstitutionDisciplines',
            [
                'institution' => fn () => $this->_institutionViewModelAssembler->assemble(
                    $institution,
                ),
                'disciplines' => static fn () => $institution->disciplines->map(
                    static fn (
                        Discipline $discipline,
                    ) => InstitutionDisciplineViewModel::fromModel($discipline),
                ),
            ],
        );
    }

    /**
     * @throws ReflectionException
     * @throws BindingResolutionException
     */
    #[Post('/{discipline}', name: 'admin.institutions.show.disciplines.store')]
    public function store(
        Institution $institution,
        Discipline $discipline,
    ): RedirectResponse {
        $this->_commandBus->dispatch(
            new AddDisciplineToInstitutionCommand(
                institution: $institution,
                discipline: $discipline,
            ),
        );

        return $this->_redirector
            ->back()
            ->with('success', [
                __('toasts.institutions.discipline_associated'),
            ]);
    }

    /**
     * @throws ReflectionException
     * @throws BindingResolutionException
     */
    #[
        Delete(
            '/{discipline}',
            name: 'admin.institutions.show.disciplines.delete',
        ),
    ]
    public function destroy(
        Institution $institution,
        Discipline $discipline,
    ): RedirectResponse {
        $this->_commandBus->dispatch(
            new RemoveDisciplineFromInstitutionCommand(
                institution: $institution,
                discipline: $discipline,
            ),
        );

        return $this->_redirector
            ->back()
            ->with('success', [__('toasts.institutions.discipline_removed')]);
    }
}
