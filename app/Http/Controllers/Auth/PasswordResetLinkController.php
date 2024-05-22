<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Session\SessionManager;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;

final class PasswordResetLinkController extends Controller
{
    function __construct(
        private readonly Redirector $_redirector,
        private readonly SessionManager $_sessionManager,
        private readonly PasswordBroker $_passwordBroker
    ) {
    }

    /**
     * Display the password reset link request view.
     */
    #[Get("/Forgot-Password", name: "password.request", middleware: "guest")]
    public function create(): Response
    {
        return Inertia::render('Auth/ForgotPassword', [
            'status' => $this->_sessionManager->get('status'),
        ]);
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws ValidationException
     */
    #[Post("/Forgot-Password", name: "password.email", middleware: "guest")]
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = $this->_passwordBroker->sendResetLink(
            $request->only('email')
        );

        if ($status == PasswordBroker::RESET_LINK_SENT) {
            return $this->_redirector->back()->with('status', __($status));
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }
}
