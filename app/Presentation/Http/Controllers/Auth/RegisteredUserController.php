<?php

namespace App\Presentation\Http\Controllers\Auth;

use App\Core\Models\Identity;
use App\Presentation\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\Factory;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

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
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     */
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

        $this->_authManager->guard()->login($identity);

        return $this->_redirector->route("dashboard");
    }
}
