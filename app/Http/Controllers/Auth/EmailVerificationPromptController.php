<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Inertia\Inertia;
use Inertia\Response;

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
    public function __invoke(Request $request): RedirectResponse|Response
    {
        return $request->user()->hasVerifiedEmail()
            ? $this->_redirector->intended(
                default: $this->_urlGenerator->route('dashboard', absolute: false)
            )
            : Inertia::render('Auth/VerifyEmail', ['status' => session('status')]);
    }
}
