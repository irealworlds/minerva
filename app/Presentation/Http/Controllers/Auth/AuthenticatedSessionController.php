<?php

namespace App\Presentation\Http\Controllers\Auth;

use App\Presentation\Http\Controllers\Controller;
use App\Presentation\Http\Requests\Auth\LoginRequest;
use Illuminate\Contracts\Auth\Factory;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Routing\Router;
use Illuminate\Session\SessionManager;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

final class AuthenticatedSessionController extends Controller
{
    function __construct(
        private readonly Router $_router,
        private readonly Factory $_authManager,
        private readonly Redirector $_redirector,
        private readonly UrlGenerator $_urlGenerator,
        private readonly SessionManager $_sessionManager
    ) {
    }

    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => $this->_router->has('password.request'),
            'status' => $this->_sessionManager->get('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     *
     * @throws ValidationException
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return $this->_redirector->intended(
            default: $this->_urlGenerator->route('dashboard', absolute: false)
        );
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $this->_authManager->guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return $this->_redirector->to('/');
    }
}
