<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers;

use App\ApplicationServices\Identities\Update\UpdateIdentityCommand;
use App\Core\Contracts\Cqrs\ICommandBus;
use App\Core\Models\Identity;
use App\Http\Web\Requests\ProfileUpdateRequest;
use Codestage\Authorization\Attributes\Authorize;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\{
    RedirectResponse,
    Request};
use Illuminate\Routing\Redirector;
use Illuminate\Session\SessionManager;
use Inertia\{
    Inertia,
    Response};
use ReflectionException;
use RuntimeException;
use Spatie\RouteAttributes\Attributes\{
    Get,
    Patch};

final readonly class ProfileController extends Controller
{
    public function __construct(
        private SessionManager $_sessionManager,
        private Redirector $_redirector,
        private ICommandBus $_commandBus
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
     *
     * @throws BindingResolutionException
     * @throws ReflectionException
     */
    #[Patch('/Profile', name: 'profile.update')]
    #[Authorize]
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        /** @var Identity $identity */
        $identity = $request->user();

        $command = new UpdateIdentityCommand($identity, $request->string('email')->toString());
        $this->_commandBus->dispatch($command);

        return $this->_redirector->route('profile.edit');
    }
}
