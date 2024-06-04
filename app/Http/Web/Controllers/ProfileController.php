<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers;

use App\ApplicationServices\Identities\Update\UpdateIdentityCommand;
use App\Core\Contracts\Cqrs\ICommandBus;
use App\Core\Models\Identity;
use App\Core\Optional;
use App\Http\Web\Requests\ProfileUpdateRequest;
use Codestage\Authorization\Attributes\Authorize;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\{RedirectResponse, Request};
use Illuminate\Routing\Redirector;
use Illuminate\Session\SessionManager;
use Illuminate\Validation\ValidationException;
use Inertia\{Inertia, Response};
use ReflectionException;
use RuntimeException;
use Spatie\RouteAttributes\Attributes\{Get, Patch};

final readonly class ProfileController extends Controller
{
    public function __construct(
        private SessionManager $_sessionManager,
        private Redirector $_redirector,
        private ICommandBus $_commandBus,
    ) {
    }

    /**
     * Display the user's profile form.
     *
     * @throws RuntimeException
     */
    #[Get('/Profile', name: 'profile.edit')]
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
     * @throws BindingResolutionException
     * @throws ReflectionException
     * @throws ValidationException
     */
    #[Patch('/Profile', name: 'profile.update')]
    #[Authorize]
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        /** @var Identity $identity */
        $identity = $request->user();

        /** @var Optional<string[]> $middleNames */
        $middleNames = $request->optionalArray('middleNames', false);

        // Dispatch the update identity command
        $command = new UpdateIdentityCommand(
            identity: $identity,
            namePrefix: $request->optionalString('namePrefix'),
            firstName: $request->optionalString('firstName', false),
            middleNames: $middleNames,
            lastName: $request->optionalString('lastName', false),
            nameSuffix: $request->optionalString('nameSuffix'),
            email: $request->optionalString('email', false),
        );
        $this->_commandBus->dispatch($command);

        // Redirect back to the profile page
        return $this->_redirector
            ->action([ProfileController::class, 'edit'])
            ->with('success', [__('toasts.profile.updated')]);
    }
}
