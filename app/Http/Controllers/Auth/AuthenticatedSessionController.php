<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Codestage\Authorization\Attributes\Authorize;
use Illuminate\Contracts\Auth\{
    Factory,
    StatefulGuard};
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Http\{
    RedirectResponse,
    Request};
use Illuminate\Routing\{
    Redirector,
    Router};
use Illuminate\Session\SessionManager;
use Illuminate\Validation\ValidationException;
use Inertia\{
    Inertia,
    Response};
use InvalidArgumentException;
use RuntimeException;
use Spatie\RouteAttributes\Attributes\{
    Get,
    Post};

final class AuthenticatedSessionController extends Controller
{
    public function __construct(
        private readonly Router $_router,
        private readonly Factory $_authManager,
        private readonly Redirector $_redirector,
        private readonly UrlGenerator $_urlGenerator,
        private readonly SessionManager $_sessionManager
    ) {
    }

    /**
     * Display the login view.
     *
     * @throws RuntimeException
     */
    #[Get('/Login', name: 'login', middleware: 'guest')]
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
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    #[Post('/Login', middleware: 'guest')]
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
     *
     * @throws RuntimeException
     */
    #[Get('/Logout', name: 'logout')]
    #[Authorize]
    public function destroy(Request $request): RedirectResponse
    {
        $guard = $this->_authManager->guard('web');
        if ($guard instanceof StatefulGuard) {
            $guard->logout();
        }

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return $this->_redirector->to('/');
    }
}
