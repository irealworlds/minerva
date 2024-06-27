<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Auth;

use App\ApplicationServices\Identities\Create\CreateIdentityCommand;
use App\ApplicationServices\Identities\FindByUsername\FindIdentityByUsernameQuery;
use App\Core\Contracts\Cqrs\{ICommandBus, IQueryBus};
use App\Core\Dtos\PersonalNameDto;
use App\Core\Optional;
use App\Http\Web\Controllers\{Controller, DashboardController};
use Illuminate\Contracts\Auth\{Factory as AuthManager, StatefulGuard};
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Http\{RedirectResponse};
use Illuminate\Routing\Redirector;
use Inertia\{Response, ResponseFactory};
use ReflectionException;
use RuntimeException;
use Spatie\RouteAttributes\Attributes\{Get, Post};

final readonly class IdentityCreationController extends Controller
{
    public function __construct(
        private AuthManager $_authManager,
        private Redirector $_redirector,
        private Hasher $_hasher,
        private ResponseFactory $_inertia,
        private ICommandBus $_commandBus,
        private IQueryBus $_queryBus,
    ) {
    }

    #[Get('/Register', name: 'register', middleware: 'guest')]
    public function create(): Response
    {
        return $this->_inertia->render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws BindingResolutionException
     * @throws ReflectionException
     * @throws RuntimeException
     */
    #[Post('/Register', middleware: 'guest')]
    public function store(IdentityCreationRequest $request): RedirectResponse
    {
        // Build and save the identity
        $this->_commandBus->dispatch(
            new CreateIdentityCommand(
                username: $request->idNumber,
                name: new PersonalNameDto(
                    prefix: empty($request->namePrefix)
                        ? null
                        : $request->namePrefix,
                    firstName: $request->firstName,
                    middleNames: $request->middleNames,
                    lastName: $request->lastName,
                    suffix: empty($request->nameSuffix)
                        ? null
                        : $request->nameSuffix,
                ),
                email: $request->email,
                password: Optional::of(
                    $this->_hasher->make($request->password),
                ),
            ),
        );

        $identity = $this->_queryBus->dispatch(
            new FindIdentityByUsernameQuery(username: $request->idNumber),
        );

        if (empty($identity)) {
            throw new RuntimeException('Could not create identity.');
        }

        // Log the user in
        $guard = $this->_authManager->guard();
        if ($guard instanceof StatefulGuard) {
            $guard->login($identity);
        }

        // Redirect the user
        return $this->_redirector->action(DashboardController::class);
    }
}
