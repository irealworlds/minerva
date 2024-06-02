<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\InstitutionEducators\Roles;

use App\ApplicationServices\Educators\RemoveRolesInInstitution\RemoveEducatorRolesFromInstitutionCommand;
use App\Core\Contracts\Cqrs\ICommandBus;
use App\Core\Models\{Educator, Institution};
use App\Http\Web\Controllers\Controller;
use App\Http\Web\Controllers\Institutions\Management\ManageInstitutionEducatorsController;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use InvalidArgumentException;
use ReflectionException;
use Spatie\RouteAttributes\Attributes\{Delete, Group};

#[
    Group(
        prefix: '/Institutions/Manage/{institution}/Educators/{educator}/Roles/{role}',
    ),
]
final readonly class DeleteInstitutionEducatorRoleController extends Controller
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
    #[Delete('/', name: 'institutions.educators.roles.delete')]
    public function __invoke(
        Institution $institution,
        Educator $educator,
        string $role,
    ): RedirectResponse {
        $this->_commandBus->dispatch(
            new RemoveEducatorRolesFromInstitutionCommand(
                educator: $educator,
                institution: $institution,
                roles: [$role],
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
                __('toasts.institutions.educators.roles.deleted', [
                    'role' => $role,
                ]),
            ]);
    }
}
