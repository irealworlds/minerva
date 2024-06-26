<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Auth\AuthSessions;

use Illuminate\Routing\Router;
use Illuminate\Session\SessionManager;
use Inertia\{Response, ResponseFactory};
use Spatie\RouteAttributes\Attributes\Get;

final readonly class CreateAuthSessionController
{
    public function __construct(
        private Router $_router,
        private SessionManager $_sessionManager,
        private ResponseFactory $_inertia,
    ) {
    }

    /**
     * Display the login view.
     */
    #[Get('/Login', name: 'login', middleware: 'guest')]
    public function __invoke(): Response
    {
        return $this->_inertia->render('Auth/Login', [
            'canResetPassword' => $this->_router->has('password.request'),
            'status' => $this->_sessionManager->get('status'),
        ]);
    }
}
