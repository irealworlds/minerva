<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

final class EmailVerificationNotificationController extends Controller
{
    function __construct(
        private readonly Redirector $_redirector,
        private readonly UrlGenerator $_urlGenerator
    ) {
    }

    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return $this->_redirector->intended(
                default: $this->_urlGenerator->route('dashboard', absolute: false)
            );
        }

        $request->user()->sendEmailVerificationNotification();

        return $this->_redirector->back()->with('status', 'verification-link-sent');
    }
}
