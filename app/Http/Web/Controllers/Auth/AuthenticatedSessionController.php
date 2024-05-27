<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Auth;

use App\Http\Web\Controllers\Controller;
use App\Http\Web\Requests\Auth\LoginRequest;
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

final readonly class AuthenticatedSessionController extends Controller
{
    public function __construct(
        private Router $_router,
        private Factory $_authManager,
        private Redirector $_redirector,
        private UrlGenerator $_urlGenerator,
        private SessionManager $_sessionManager,
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

        return $this->_redirector
            ->intended(
                default: $this->_urlGenerator->route(
                    'dashboard',
                    absolute: false,
                ),
            )
            ->with('success', [__('toasts.login.success')]);
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

        return $this->_redirector
            ->to('/')
            ->with('success', [__('toasts.logout.success')]);
    }
}
