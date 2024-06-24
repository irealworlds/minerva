<?php

declare(strict_types=1);

namespace App\Http\Web\Middleware;

use App\Core\Models\Identity;
use App\Http\Web\ViewModels\AuthenticatedUserViewModel;
use Closure;
use Illuminate\Http\Request;
use Inertia\Middleware as InertiaMiddleware;
use Tighten\Ziggy\Ziggy;

final class HandleInertiaRequestsMiddleware extends InertiaMiddleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array{errors: Closure, auth: array{user: AuthenticatedUserViewModel|null}, ziggy: Closure}
     */
    public function share(Request $request): array
    {
        /** @var Identity|null $user */
        $user = $request->user();

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $user
                    ? new AuthenticatedUserViewModel(
                        id: $user->getKey(),
                        name: $user->name,
                        email: $user->email,
                        emailVerified: $user->hasVerifiedEmail(),
                        pictureUri: 'https://ui-avatars.com/api/?name=' .
                            urlencode($user->email) .
                            '&background=random&size=128',
                        permissions: $user->getPermissions(),
                    )
                    : null,
            ],
            'toasts' => [
                'info' => fn() => $request->session()->get('info', []),
                'success' => fn() => $request->session()->get('success', []),
                'warning' => fn() => $request->session()->get('warning', []),
                'error' => fn() => $request->session()->get('error', []),
                'default' => fn() => $request->session()->get('default', []),
            ],
            'ziggy' => static fn() => [
                ...(new Ziggy())->toArray(),
                'location' => $request->url(),
            ],
        ];
    }
}
