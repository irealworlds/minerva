<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

final class VerifyEmailController extends Controller
{
    function __construct(
        private readonly Redirector $_redirector,
        private readonly UrlGenerator $_urlGenerator,
        private readonly Dispatcher $_eventDispatcher
    ) {
    }

    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return $this->_redirector->intended(
                default: $this->_urlGenerator->route('dashboard', absolute: false).'?verified=1'
            );
        }

        if ($request->user()->markEmailAsVerified()) {
            $this->_eventDispatcher->dispatch(new Verified($request->user()));
        }

        return $this->_redirector->intended(
            default: $this->_urlGenerator->route('dashboard', absolute: false).'?verified=1'
        );
    }
}
