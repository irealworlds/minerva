<?php

declare(strict_types=1);

namespace App\Http\Api\Endpoints\Admin\EducatorInvitations;

use App\ApplicationServices\EducatorInvitations\Create\CreateEducatorInvitationCommand;
use App\ApplicationServices\EducatorInvitations\ListOutstandingForInstitution\ListOutstandingInvitationsForInstitutionQuery;
use App\ApplicationServices\Educators\FindByEmail\FindEducatorByEmailQuery;
use App\ApplicationServices\Institutions\FindById\FindInstitutionsByRouteKeysQuery;
use App\Core\Contracts\Cqrs\{ICommandBus, IQueryBus};
use App\Core\Models\{Educator, EducatorInvitation, Identity, Institution};
use App\Http\Api\Endpoints\Endpoint;
use App\Http\Web\Controllers\Admin\EducatorInvitations\CreateEducatorInvitationRequest;
use Codestage\Authorization\Attributes\Authorize;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Factory as AuthManager;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use ReflectionException;
use Spatie\RouteAttributes\Attributes\Post;

final readonly class CreateEducatorInvitationEndpoint extends Endpoint
{
    public function __construct(
        private IQueryBus $_queryBus,
        private ICommandBus $_commandBus,
        private AuthManager $_authManager,
    ) {
    }

    /**
     * @throws AuthenticationException
     * @throws ValidationException
     * @throws BindingResolutionException
     * @throws ReflectionException
     * @throws InvalidArgumentException
     */
    #[
        Post(
            '/Admin/EducatorInvitations',
            name: 'api.admin.educator_invitations.create',
        ),
    ]
    #[Authorize]
    public function __invoke(CreateEducatorInvitationRequest $request): Response
    {
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
        // Redirect back
        return new Response(status: 201);
    }
}
