<?php

namespace App\Http\Middleware;

use App\Http\ViewModels\AuthenticatedUserViewModel;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
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
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user() ? new AuthenticatedUserViewModel(
                    id: $request->user()->getKey(),
                    email: $request->user()->email,
                    emailVerified: $request->user()->hasVerifiedEmail(),
                    pictureUri: "https://ui-avatars.com/api/?name=" . urlencode($request->user()->email) . "&background=random&size=128"
                ) : null,
            ],
            'ziggy' => fn () => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
        ];
    }
}
