<?php

namespace App\Http\Controllers\Auth;

use App\Core\Models\Identity;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\Factory;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use RuntimeException;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;

final class RegisteredUserController extends Controller
{
    function __construct(
        private readonly Dispatcher $_eventDispatcher,
        private readonly Factory $_authManager,
        private readonly Redirector $_redirector,
        private readonly Hasher $_hasher
    ) {
    }

    /**
     * Display the registration view.
     *
     * @throws RuntimeException
     */
    #[Get("/Register", name: "register", middleware: "guest")]
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    #[Post("/Register", middleware: "guest")]
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|string|lowercase|email|max:255|unique:'.Identity::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        /** @var Identity $identity */
        $identity = Identity::query()->create([
            'email' => $request->string("email"),
            'password' => $this->_hasher->make($request->string("password")),
        ]);

        $this->_eventDispatcher->dispatch(new Registered($identity));

        $guard = $this->_authManager->guard();
        if ($guard instanceof StatefulGuard) {
            $guard->login($identity);
        }

        return $this->_redirector->route("dashboard");
    }
}
