<?php

namespace App\Http\Controllers;

use App\ApplicationServices\Identities\Update\UpdateIdentityCommand;
use App\Core\Contracts\Cqrs\ICommandBus;
use App\Http\Requests\ProfileUpdateRequest;
use Codestage\Authorization\Attributes\Authorize;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Session\SessionManager;
use Inertia\Inertia;
use Inertia\Response;
use ReflectionException;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Patch;

final class ProfileController extends Controller
{
    function __construct(
        private readonly SessionManager $_sessionManager,
        private readonly Redirector $_redirector,
        private readonly ICommandBus $_commandBus
    ) {
    }

    /**
     * Display the user's profile form.
     */
    #[Get("/Profile", name: "profile.edit")]
    #[Authorize]
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => $this->_sessionManager->get('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     *
     *
     * @throws BindingResolutionException
     * @throws ReflectionException
     */
    #[Patch("/Profile", name: "profile.update")]
    #[Authorize]
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $command = new UpdateIdentityCommand($request->user(), $request->string("email"));
        $this->_commandBus->dispatch($command);

        return $this->_redirector->route('profile.edit');
    }
}
