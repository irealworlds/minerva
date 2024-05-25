<?php

namespace App\Http\Controllers\Auth;

use App\Core\Models\Identity;
use App\Http\Controllers\Controller;
use Codestage\Authorization\Attributes\Authorize;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\Auth\Factory as AuthManager;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use InvalidArgumentException;
use Spatie\RouteAttributes\Attributes\Get;
use UnexpectedValueException;

final class VerifyEmailController extends Controller
{
    function __construct(
        private readonly Redirector $_redirector,
        private readonly UrlGenerator $_urlGenerator,
        private readonly Dispatcher $_eventDispatcher,
        private readonly AuthManager $_authManager
    ) {
    }

    /**
     * Mark the authenticated user's email address as verified.
     *
     * @throws AuthenticationException
     * @throws UnexpectedValueException
     * @throws InvalidArgumentException
     */
    #[Get("Verify-Email/{id}/{hash}", name: "verification.verify", middleware: ["signed", "throttle:6,1"])]
    #[Authorize]
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        /** @var Identity|null $user */
        $user = $this->_authManager->guard()->user();

        if (!$user) {
            throw new AuthenticationException();
        }

        if(!($user instanceof MustVerifyEmail)) {
            throw new UnexpectedValueException("Identity class [" . get_class($user) . "] doesn't implement the [MustVerifyEmail] contract.");
        }

        if ($user->hasVerifiedEmail()) {
            return $this->_redirector->intended(
                default: $this->_urlGenerator->route('dashboard', absolute: false).'?verified=1'
            );
        }

        if ($user->markEmailAsVerified()) {
            $this->_eventDispatcher->dispatch(new Verified($user));
        }

        return $this->_redirector->intended(
            default: $this->_urlGenerator->route('dashboard', absolute: false).'?verified=1'
        );
    }
}
