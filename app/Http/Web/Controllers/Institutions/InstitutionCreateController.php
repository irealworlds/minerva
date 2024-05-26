<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Institutions;

use App\ApplicationServices\Institutions\Create\CreateInstitutionCommand;
use App\ApplicationServices\Institutions\UpdatePicture\UpdateInstitutionPictureCommand;
use App\Core\Contracts\Cqrs\ICommandBus;
use App\Core\Enums\Permission;
use App\Core\Models\Institution;
use App\Http\Web\Controllers\Controller;
use App\Http\Web\Requests\Institutions\InstitutionCreateRequest;
use Codestage\Authorization\Attributes\Authorize;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Inertia\{
    Inertia,
    Response as InertiaResponse};
use ReflectionException;
use RuntimeException;
use Spatie\RouteAttributes\Attributes\{
    Get,
    Post,
    Prefix};
use Throwable;

#[Prefix('/Institutions/Create')]
#[Authorize(permissions: Permission::InstitutionsCreate)]
final readonly class InstitutionCreateController extends Controller
{
    public function __construct(
        private Redirector $_redirector,
        private ICommandBus $_commandBus
    ) {
    }

    /**
     * @throws RuntimeException
     */
    #[Get('/', name: 'institutions.create')]
    public function create(): InertiaResponse
    {
        return Inertia::render('Institutions/Create');
    }

    /**
     * @throws Throwable
     * @throws ReflectionException
     * @throws BindingResolutionException
     * @throws ValidationException
     */
    #[Post('/', name: 'institutions.store')]
    public function store(InstitutionCreateRequest $request): RedirectResponse
    {
        // Create a new institution
        $id = Str::uuid();
        $this->_commandBus->dispatch(new CreateInstitutionCommand(
            id: $id->toString(),
            name: $request->name,
            website: $request->website,
            parentId: $request->parentInstitutionId
        ));

        /** @var Institution $institution */
        $institution = Institution::query()->findOrFail($id);

        // Update the picture on the institution
        try {
            if ($request->picture) {
                try {
                    /** @throws ValidationException */
                    $this->_commandBus->dispatch(new UpdateInstitutionPictureCommand(
                        institution: $institution,
                        newPicture: $request->picture
                    ));
                } catch (ValidationException $e) {
                    $errors = $e->errors();

                    if (isset($errors['newPicture'])) {
                        $errors['picture'] = $errors['newPicture'];
                        unset($errors['newPicture']);
                    }

                    throw ValidationException::withMessages($errors);
                }
            }
        } catch (Throwable $t) {
            $institution->delete();
            throw $t;
        }

        // Redirect to the institution details page
        return $this->_redirector->action(InstitutionReadController::class, [
            'institution' => $institution->getRouteKey()
        ])->with("success", [__("toasts.institutions.created")]);
    }
}