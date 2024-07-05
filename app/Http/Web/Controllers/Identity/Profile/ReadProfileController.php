<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Identity\Profile;

use App\Http\Web\Controllers\Controller;
use Codestage\Authorization\Attributes\Authorize;
use Inertia\{Response, ResponseFactory};
use Illuminate\Contracts\Auth\{Factory as IAuthManager, MustVerifyEmail};
use Illuminate\Http\Request;
use Illuminate\Session\SessionManager;
use Spatie\RouteAttributes\Attributes\Get;

final readonly class ReadProfileController extends Controller
{
    public function __construct(
        private SessionManager $_sessionManager,
        private ResponseFactory $_inertia,
        private IAuthManager $_authManager,
    ) {
    }

    /**
     * Display the user's profile form.
     */
    #[Get('/Profile', name: 'profile.edit')]
    #[Authorize]
    public function __invoke(Request $request): Response
    {
        $identity = $this->_authManager->guard()->user();

        return $this->_inertia->render('Profile/Edit', [
            'mustVerifyEmail' => $identity instanceof MustVerifyEmail,
            'status' => $this->_sessionManager->get('status'),
        ]);
    }
}
