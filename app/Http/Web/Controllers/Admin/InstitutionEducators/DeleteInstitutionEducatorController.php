<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Admin\InstitutionEducators;

use App\ApplicationServices\Institutions\RemoveEducator\RemoveInstitutionEducatorCommand;
use App\Core\Contracts\Cqrs\ICommandBus;
use App\Core\Models\{Educator, Institution};
use App\Http\Web\Controllers\Admin\Institutions\Management\ManageInstitutionEducatorsController;
use App\Http\Web\Controllers\Controller;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use InvalidArgumentException;
use ReflectionException;
use Spatie\RouteAttributes\Attributes\{Delete, Group};

#[
    Group(
        prefix: '/Admin/Institutions/Manage/{institution}/Educators/{educator}',
    ),
]
final readonly class DeleteInstitutionEducatorController extends Controller
{
    public function __construct(
        private ICommandBus $_commandBus,
        private Redirector $_redirector,
    ) {
    }

    /**
     * @throws BindingResolutionException
     * @throws ReflectionException
     * @throws InvalidArgumentException
     */
    #[Delete('/', name: 'admin.institutions.educators.delete')]
    public function __invoke(
        Institution $institution,
        Educator $educator,
    ): RedirectResponse {
        $this->_commandBus->dispatch(
            new RemoveInstitutionEducatorCommand(
                institution: $institution,
                educator: $educator,
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
            ->with('success', [__('toasts.institutions.educators.created')]);
    }
}
