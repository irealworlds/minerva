<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Institutions;

use App\ApplicationServices\Institutions\Delete\DeleteInstitutionCommand;
use App\Core\Contracts\Cqrs\ICommandBus;
use App\Core\Enums\Permission;
use App\Core\Models\Institution;
use Codestage\Authorization\Attributes\Authorize;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use ReflectionException;
use Spatie\RouteAttributes\Attributes\Delete;

final readonly class InstitutionDeleteController
{
    public function __construct(
        private ICommandBus $_commandBus,
        private Redirector $_redirector,
    ) {
    }

    /**
     * @throws ReflectionException
     * @throws BindingResolutionException
     */
    #[Delete('/Institutions/{institution}', name: 'institutions.delete')]
    #[Authorize(permissions: Permission::InstitutionDelete)]
    public function __invoke(Institution $institution): RedirectResponse
    {
        $this->_commandBus->dispatch(
            new DeleteInstitutionCommand($institution),
        );

        return $this->_redirector
            ->action(InstitutionListController::class)
            ->with('success', [__('toasts.institutions.deleted')]);
    }
}
