<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Auth;

use App\Core\Models\Identity;
use App\Http\Web\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\{
    Factory,
    StatefulGuard};
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Http\{
    RedirectResponse,
    Request};
use Illuminate\Routing\Redirector;
use Illuminate\Validation\{
    Rules,
    ValidationException};
use Inertia\{
    Inertia,
    Response};
use RuntimeException;
use Spatie\RouteAttributes\Attributes\{
    Get,
    Post};

final readonly class RegisteredUserController extends Controller
{
    public function __construct(
        private Dispatcher $_eventDispatcher,
        private Factory $_authManager,
        private Redirector $_redirector,
        private Hasher $_hasher
    ) {
    }

    /**
     * Display the registration view.
     *
     * @throws RuntimeException
     */
    #[Get('/Register', name: 'register', middleware: 'guest')]
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    #[Post('/Register', middleware: 'guest')]
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|string|lowercase|email|max:255|unique:'.Identity::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        /** @var Identity $identity */
        $identity = Identity::query()->create([
            'email' => $request->string('email'),
            'password' => $this->_hasher->make($request->string('password')->toString()),
        ]);

        $this->_eventDispatcher->dispatch(new Registered($identity));

        $guard = $this->_authManager->guard();
        if ($guard instanceof StatefulGuard) {
            $guard->login($identity);
        }

        return $this->_redirector->route('dashboard');
    }
}
