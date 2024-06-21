<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Admin\InstitutionEducators\Roles;

use App\ApplicationServices\Educators\AddRolesInInstitution\AddEducatorRolesInInstitutionCommand;
use App\Core\Contracts\Cqrs\ICommandBus;
use App\Core\Models\{Educator, Institution};
use App\Http\Web\Controllers\Admin\Institutions\Management\ManageInstitutionEducatorsController;
use App\Http\Web\Controllers\Controller;
use App\Http\Web\Requests\InstitutionEducators\Roles\CreateInstitutionEducatorRolesRequest;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use InvalidArgumentException;
use ReflectionException;
use Spatie\RouteAttributes\Attributes\{Group, Post};

#[
    Group(
        prefix: '/Admin/Institutions/Manage/{institution}/Educators/{educator}/Roles',
    ),
]
final readonly class CreateInstitutionEducatorRolesController extends Controller
{
    public function __construct(
        private ICommandBus $_commandBus,
        private Redirector $_redirector,
    ) {
    }

    /**
     * @throws ReflectionException
     * @throws BindingResolutionException
     * @throws InvalidArgumentException
     */
    #[Post('/', name: 'admin.institutions.educators.roles.create')]
    public function __invoke(
        CreateInstitutionEducatorRolesRequest $request,
        Institution $institution,
        Educator $educator,
    ): RedirectResponse {
        $this->_commandBus->dispatch(
            new AddEducatorRolesInInstitutionCommand(
                educator: $educator,
                institution: $institution,
                roles: $request->roles,
            ),
        );

        return $this->_redirector
            ->back(
                fallback: $this->_redirector
                    ->getUrlGenerator()
                    ->action(ManageInstitutionEducatorsController::class, [
                        'institution' => $institution->getRouteKey(),
                    ]),
            )
            ->with('success', [
                __('toasts.institutions.educators.roles.created', [
                    'role' => implode(', ', [...$request->roles]),
                ]),
            ]);
    }
}
