<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Identity;

use App\ApplicationServices\Identities\UpdatePassword\UpdateIdentityPasswordCommand;
use App\Core\Contracts\Cqrs\ICommandBus;
use App\Core\Models\Identity;
use App\Http\Web\Controllers\Auth\AuthSessions\CreateAuthSessionController;
use App\Http\Web\Controllers\Controller;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use ReflectionException;
use Spatie\RouteAttributes\Attributes\Post;

final readonly class StorePasswordController extends Controller
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
    #[Post('/Identities/{identity}/Password/Create', middleware: ['signed'])]
    public function __invoke(
        StorePasswordRequest $request,
        Identity $identity,
    ): RedirectResponse {
        $this->_commandBus->dispatch(
            new UpdateIdentityPasswordCommand(
                identity: $identity,
                password: $request->password,
            ),
        );

        return $this->_redirector
            ->action(CreateAuthSessionController::class)
            ->with('success', [__('toasts.identity.password.created')]);
    }
}
