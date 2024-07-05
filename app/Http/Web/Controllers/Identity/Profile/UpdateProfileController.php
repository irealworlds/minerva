<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers;

use App\ApplicationServices\Identities\Update\UpdateIdentityCommand;
use App\Core\Contracts\Cqrs\ICommandBus;
use App\Core\Models\Identity;
use App\Core\Optional;
use App\Http\Web\Controllers\Identity\Profile\ReadProfileController;
use Codestage\Authorization\Attributes\Authorize;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\{RedirectResponse};
use Illuminate\Routing\Redirector;
use Illuminate\Validation\ValidationException;
use ReflectionException;
use Spatie\RouteAttributes\Attributes\{Patch};

final readonly class UpdateProfileController extends Controller
{
    public function __construct(
        private Redirector $_redirector,
        private ICommandBus $_commandBus,
    ) {
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
    public function __invoke(UpdateProfileRequest $request): RedirectResponse
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
            ->action(ReadProfileController::class)
            ->with('success', [__('toasts.profile.updated')]);
    }
}
