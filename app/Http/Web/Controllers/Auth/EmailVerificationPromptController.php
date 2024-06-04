<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Auth;

use App\Core\Models\Identity;
use App\Http\Web\Controllers\Controller;
use Codestage\Authorization\Attributes\Authorize;
use Illuminate\Contracts\Auth\Factory as AuthManager;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Http\{RedirectResponse, Request};
use Illuminate\Routing\Redirector;
use Inertia\{Inertia, Response};
use InvalidArgumentException;
use RuntimeException;
use Spatie\RouteAttributes\Attributes\Get;

final readonly class EmailVerificationPromptController extends Controller
{
    public function __construct(
        private Redirector $_redirector,
        private UrlGenerator $_urlGenerator,
        private AuthManager $_authManager,
    ) {
    }

    /**
     * Display the email verification prompt.
     *
     * @throws RuntimeException
     * @throws InvalidArgumentException
     */
    #[Get('Verify-Email', name: 'verification.notice')]
    #[Authorize]
    public function __invoke(Request $request): RedirectResponse|Response
    {
        /** @var Identity $user */
        $user = $this->_authManager->guard();

        return $user->hasVerifiedEmail()
            ? $this->_redirector->intended(
                default: $this->_urlGenerator->route(
                    'dashboard',
                    absolute: false,
                ),
            )
            : Inertia::render('Auth/VerifyEmail', [
                'status' => session('status'),
            ]);
    }
}
