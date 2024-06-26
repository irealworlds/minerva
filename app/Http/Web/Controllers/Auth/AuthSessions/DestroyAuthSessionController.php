<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Auth\AuthSessions;

use Codestage\Authorization\Attributes\Authorize;
use Illuminate\Contracts\Auth\{Factory, StatefulGuard};
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Session\SessionManager;
use Spatie\RouteAttributes\Attributes\Get;

final readonly class DestroyAuthSessionController
{
    public function __construct(
        private Factory $_authManager,
        private Redirector $_redirector,
        private SessionManager $_sessionManager,
    ) {
    }

    /**
     * Destroy an authenticated session.
     */
    #[Get('/Logout', name: 'logout')]
    #[Authorize]
    public function destroy(): RedirectResponse
    {
        $guard = $this->_authManager->guard();
        if ($guard instanceof StatefulGuard) {
            $guard->logout();
        }

        $this->_sessionManager->invalidate();
        $this->_sessionManager->regenerateToken();

        return $this->_redirector
            ->to('/')
            ->with('success', [__('toasts.logout.success')]);
    }
}
