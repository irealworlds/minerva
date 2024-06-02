<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\EducatorInvitations;

use App\ApplicationServices\EducatorInvitations\Create\CreateEducatorInvitationCommand;
use App\ApplicationServices\EducatorInvitations\ListOutstandingForInstitution\ListOutstandingInvitationsForInstitutionQuery;
use App\ApplicationServices\Educators\FindByEmail\FindEducatorByEmailQuery;
use App\ApplicationServices\Institutions\FindById\FindInstitutionsByRouteKeysQuery;
use App\Core\Contracts\Cqrs\{ICommandBus, IQueryBus};
use App\Core\Models\{Educator, EducatorInvitation, Identity, Institution};
use App\Http\Web\Controllers\Controller;
use App\Http\Web\Controllers\Institutions\Management\ManageInstitutionEducatorsController;
use App\Http\Web\Requests\EducatorInstitutions\EducatorInstitutionCreationRequest;
use Codestage\Authorization\Attributes\Authorize;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Factory as AuthManager;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Validation\ValidationException;
use ReflectionException;
use Spatie\RouteAttributes\Attributes\Post;

final readonly class CreateEducatorInvitationController extends Controller
{
    public function __construct(
        private IQueryBus $_queryBus,
        private ICommandBus $_commandBus,
        private Redirector $_redirector,
        private UrlGenerator $_urlGenerator,
        private AuthManager $_authManager,
    ) {
    }

    /**
     * @throws AuthenticationException
     * @throws ValidationException
     * @throws BindingResolutionException
     * @throws ReflectionException
     */
    #[Post('/EducatorInvitations', name: 'educator_invitations.create')]
    #[Authorize]
    public function __invoke(
        EducatorInstitutionCreationRequest $request,
    ): RedirectResponse {
        // Get the institution from the request
        /** @var Institution|null $institution */
        $institution = $this->_queryBus
            ->dispatch(
                new FindInstitutionsByRouteKeysQuery($request->institutionKey),
            )
            ->first();
        if (empty($institution)) {
            throw ValidationException::withMessages([
                'institutionKey' => __('validation.exists', [
                    'attribute' => 'institution key',
                ]),
            ]);
        }

        // Find the educator by the email
        $educator = $this->_queryBus->dispatch(
            new FindEducatorByEmailQuery($request->email),
        );
        if (empty($educator)) {
            throw ValidationException::withMessages([
                'email' => __('validation.exists', ['attribute' => 'email']),
            ]);
        }

        // Check if the educator is already an educator of the institution
        if (
            $institution->educators->some(
                fn (Educator $e) => $e->getKey() === $educator->getKey(),
            )
        ) {
            throw ValidationException::withMessages([
                'email' => __('validation.unique', ['attribute' => 'email']),
            ]);
        }

        // Check if the educator is already invited to the institution
        $invitations = $this->_queryBus->dispatch(
            new ListOutstandingInvitationsForInstitutionQuery($institution),
        );
        if (
            $invitations->some(
                fn (
                    EducatorInvitation $invitation,
                ) => $invitation->invited_educator_id === $educator->getKey(),
            )
        ) {
            throw ValidationException::withMessages([
                'email' => __('validation.unique', ['attribute' => 'email']),
            ]);
        }

        // Get the current identity
        $currentIdentity = $this->_authManager->guard()->user();
        if (!($currentIdentity instanceof Identity)) {
            throw new AuthenticationException();
        }

        // Dispatch the command to create the educator invitation
        $this->_commandBus->dispatch(
            new CreateEducatorInvitationCommand(
                institution: $institution,
                educator: $educator,
                inviter: $currentIdentity,
                roles: $request->roles,
            ),
        );

        // Build the return url
        $returnUri = $this->_urlGenerator->previous(
            fallback: $this->_urlGenerator->action(
                action: ManageInstitutionEducatorsController::class,
                parameters: [
                    'institution' => $institution->getRouteKey(),
                ],
            ),
        );

        // Redirect back
        return $this->_redirector
            ->to($returnUri)
            ->with('success', [__('toasts.educatorInvitations.created')]);
    }
}
