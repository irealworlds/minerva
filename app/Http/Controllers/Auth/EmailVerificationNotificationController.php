<?php

namespace App\Http\Controllers\Auth;

use App\Core\Models\Identity;
use App\Http\Controllers\Controller;
use Codestage\Authorization\Attributes\Authorize;
use Illuminate\Contracts\Auth\Factory as AuthManager;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use InvalidArgumentException;
use Spatie\RouteAttributes\Attributes\Post;

final class EmailVerificationNotificationController extends Controller
{
    function __construct(
        private readonly Redirector $_redirector,
        private readonly UrlGenerator $_urlGenerator,
        private readonly AuthManager $_authManager
    ) {
    }

    /**
     * Send a new email verification notification.
     *
     * @throws InvalidArgumentException
     */
    #[Post("/Email/Verification-Notification", name: "verification.send", middleware: "throttle:6,1")]
    #[Authorize]
    public function store(): RedirectResponse
    {
        /** @var Identity $user */
        $user = $this->_authManager->guard();

        if ($user->hasVerifiedEmail()) {
            return $this->_redirector->intended(
                default: $this->_urlGenerator->route('dashboard', absolute: false)
            );
        }

        $user->sendEmailVerificationNotification();

        return $this->_redirector->back()->with('status', 'verification-link-sent');
    }
}
