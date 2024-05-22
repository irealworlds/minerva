<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Codestage\Authorization\Attributes\Authorize;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\RouteAttributes\Attributes\Get;

final class EmailVerificationPromptController extends Controller
{
    function __construct(
        private readonly Redirector $_redirector,
        private readonly UrlGenerator $_urlGenerator
    ) {
    }

    /**
     * Display the email verification prompt.
     */
    #[Get("Verify-Email", name: "verification.notice")]
    #[Authorize]
    public function __invoke(Request $request): RedirectResponse|Response
    {
        return $request->user()->hasVerifiedEmail()
            ? $this->_redirector->intended(
                default: $this->_urlGenerator->route('dashboard', absolute: false)
            )
            : Inertia::render('Auth/VerifyEmail', ['status' => session('status')]);
    }
}
