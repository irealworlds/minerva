<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Auth;

use App\Core\Models\Identity;
use App\Http\Web\Controllers\Controller;
use Codestage\Authorization\Attributes\Authorize;
use Illuminate\Contracts\Auth\Factory as AuthManager;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Http\{
    RedirectResponse,
    Request};
use Illuminate\Routing\Redirector;
use Illuminate\Validation\ValidationException;
use Inertia\{
    Inertia,
    Response};
use InvalidArgumentException;
use RuntimeException;
use Spatie\RouteAttributes\Attributes\{
    Get,
    Post};

final readonly class ConfirmablePasswordController extends Controller
{
    public function __construct(
        private AuthManager $_authManager,
        private Redirector $_redirector,
        private UrlGenerator $_urlGenerator,
    ) {
    }

    /**
     * Show the confirm password view.
     *
     * @throws RuntimeException
     */
    #[Get('/Confirm-Password', name: 'password.confirm')]
    #[Authorize]
    public function show(): Response
    {
        return Inertia::render('Auth/ConfirmPassword');
    }

    /**
     * Confirm the user's password.
     *
     * @throws RuntimeException
     * @throws InvalidArgumentException
     * @throws ValidationException
     */
    #[Post('/Confirm-Password')]
    #[Authorize]
    public function store(Request $request): RedirectResponse
    {
        /** @var Identity $user */
        $user = $this->_authManager->guard();

        if (
            !$this->_authManager->guard('web')->validate([
                'email' => $user->email,
                'password' => $request->string('password'),
            ])
        ) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        $request->session()->put('auth.password_confirmed_at', time());

        return $this->_redirector->intended(
            default: $this->_urlGenerator->route('dashboard', absolute: false),
        );
    }
}
