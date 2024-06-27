<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Auth\AuthSessions;

use App\ApplicationServices\Identities\FindByUsername\FindIdentityByUsernameQuery;
use App\Core\Contracts\Cqrs\IQueryBus;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Cache\RateLimiter;
use Illuminate\Contracts\Auth\Factory as AuthManager;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Session\SessionManager;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use Spatie\RouteAttributes\Attributes\Post;

final readonly class StoreAuthSessionController
{
    public function __construct(
        private AuthManager $_authManager,
        private IQueryBus $_queryBus,
        private Redirector $_redirector,
        private UrlGenerator $_urlGenerator,
        private SessionManager $_sessionManager,
        private Dispatcher $_eventDispatcher,
        private RateLimiter $_rateLimiter,
        private Hasher $_hasher,
    ) {
    }

    /**
     * Handle an incoming authentication request.
     *
     * @throws ValidationException
     * @throws InvalidArgumentException
     */
    #[Post('/Login', middleware: 'guest')]
    public function __invoke(StoreAuthSessionRequest $request): RedirectResponse
    {
        $this->createAuthSession($request);

        $this->_sessionManager->regenerate();

        return $this->_redirector
            ->intended(
                default: $this->_urlGenerator->route(
                    'dashboard',
                    absolute: false,
                ),
            )
            ->with('success', [__('toasts.login.success')]);
    }

    /**
     * @throws ValidationException
     */
    protected function createAuthSession(StoreAuthSessionRequest $request): void
    {
        // Ensure the login request is not rate limited
        $this->ensureIsNotRateLimited($request);

        // Hit the rate limiter
        $this->_rateLimiter->hit($this->getThrottleKey($request));

        // Get the identity by username
        $identity = $this->_queryBus->dispatch(
            new FindIdentityByUsernameQuery($request->username),
        );
        if (empty($identity)) {
            throw ValidationException::withMessages([
                'username' => __('validation.exists', [
                    'attribute' => 'username',
                ]),
            ]);
        }

        // If the identity has no password, they cannot log in
        if (empty($identity->getAuthPassword())) {
            throw ValidationException::withMessages([
                'username' => __('auth.auth_type_not_supported'),
            ]);
        }

        // Check if the password matches
        $passwordMatches = $this->_hasher->check(
            $request->password,
            $identity->getAuthPassword(),
        );
        if (!$passwordMatches) {
            throw ValidationException::withMessages([
                'username' => trans('auth.failed'),
                'password' => trans('auth.failed'),
            ]);
        }

        // Log the user in
        $this->_authManager
            ->guard()
            ->login($identity, $request->boolean('remember'));

        // Clear the rate limiter
        $this->_rateLimiter->clear($this->getThrottleKey($request));
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    protected function ensureIsNotRateLimited(
        StoreAuthSessionRequest $request,
    ): void {
        $hasHadTooManyRequests = $this->_rateLimiter->tooManyAttempts(
            $this->getThrottleKey($request),
            5,
        );

        if ($hasHadTooManyRequests) {
            $this->_eventDispatcher->dispatch(new Lockout($request));

            $seconds = $this->_rateLimiter->availableIn(
                $this->getThrottleKey($request),
            );

            throw ValidationException::withMessages([
                'email' => trans('auth.throttle', [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds / 60),
                ]),
            ]);
        }
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    protected function getThrottleKey(StoreAuthSessionRequest $request): string
    {
        return Str::transliterate(
            Str::lower($request->string('email')->toString()) .
                '|' .
                $request->ip(),
        );
    }
}
