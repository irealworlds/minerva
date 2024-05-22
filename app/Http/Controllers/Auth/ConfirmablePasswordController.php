<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Codestage\Authorization\Attributes\Authorize;
use Illuminate\Contracts\Auth\Factory as AuthManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;

final class ConfirmablePasswordController extends Controller
{
    function __construct(
        private readonly AuthManager $_authManager,
    ) {
    }

    /**
     * Show the confirm password view.
     */
    #[Get("/Confirm-Password", name: "password.confirm")]
    #[Authorize]
    public function show(): Response
    {
        return Inertia::render('Auth/ConfirmPassword');
    }

    /**
     * Confirm the user's password.
     *
     * @throws ValidationException
     */
    #[Post("/Confirm-Password")]
    #[Authorize]
    public function store(Request $request): RedirectResponse
    {
        if (! $this->_authManager->guard('web')->validate([
            'email' => $this->_authManager->guard()->user()->email,
            'password' => $request->string("password"),
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        $request->session()->put('auth.password_confirmed_at', time());

        return redirect()->intended(route('dashboard', absolute: false));
    }
}
