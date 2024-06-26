<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Auth;

use App\Core\Dtos\PersonalNameDto;
use App\Core\Models\Identity;
use App\Http\Web\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\{Factory as AuthManager, StatefulGuard};
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Http\{RedirectResponse};
use Illuminate\Routing\Redirector;
use Illuminate\Validation\{ValidationException};
use Inertia\{Response, ResponseFactory};
use Spatie\RouteAttributes\Attributes\{Get, Post};
use Throwable;

final readonly class IdentityCreationController extends Controller
{
    public function __construct(
        private EventDispatcher $_eventDispatcher,
        private AuthManager $_authManager,
        private Redirector $_redirector,
        private Hasher $_hasher,
        private ResponseFactory $_inertia,
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
     * @throws ValidationException
     * @throws Throwable
     */
    #[Post('/Register', middleware: 'guest')]
    public function store(IdentityCreationRequest $request): RedirectResponse
    {
        // Build and save the identity
        /** @var Identity $identity */
        $identity = Identity::query()->make();
        $identity->username = $request->idNumber;
        $identity->name = new PersonalNameDto(
            prefix: empty($request->namePrefix) ? null : $request->namePrefix,
            firstName: $request->firstName,
            middleNames: $request->middleNames,
            lastName: $request->lastName,
            suffix: empty($request->nameSuffix) ? null : $request->nameSuffix,
        );
        $identity->email = $request->email;
        $identity->password = $this->_hasher->make($request->password);
        $identity->saveOrFail();

        // Dispatch the registered event
        $this->_eventDispatcher->dispatch(new Registered($identity));

        // Log the user in
        $guard = $this->_authManager->guard();
        if ($guard instanceof StatefulGuard) {
            $guard->login($identity);
        }

        // Redirect the user
        return $this->_redirector->route('dashboard');
    }
}
