<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Institutions;

use App\ApplicationServices\Institutions\UpdateDetails\UpdateInstitutionDetailsCommand;
use App\ApplicationServices\Institutions\UpdatePicture\UpdateInstitutionPictureCommand;
use App\Core\Contracts\Cqrs\ICommandBus;
use App\Core\Models\Institution;
use App\Http\Web\Controllers\Controller;
use App\Http\Web\Requests\Institutions\InstitutionPublicProfileUpdateRequest;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use ReflectionException;
use Spatie\RouteAttributes\Attributes\{
    Patch,
    Prefix};

#[Prefix('/Institutions/{institution}')]
final readonly class InstitutionUpdateController extends Controller
{
    public function __construct(
        private ICommandBus $_commandBus,
        private Redirector $_redirector
    ) {
    }

    /**
     * @throws ReflectionException
     * @throws BindingResolutionException
     * @throws Exception
     */
    #[Patch('/PublicProfile', name: 'institutions.update.public')]
    public function __invoke(Institution $institution, InstitutionPublicProfileUpdateRequest $request): RedirectResponse
    {
        $this->_commandBus->dispatch(new UpdateInstitutionDetailsCommand(
            institution: $institution,
            name: $request->optionalString('name', false),
            website: $request->optionalString('website')
        ));

        $picture = $request->optionalFile('picture');
        if ($picture->hasValue()) {
            $this->_commandBus->dispatch(new UpdateInstitutionPictureCommand(
                institution: $institution,
                newPicture: $picture->value
            ));
        }

        return $this->_redirector->back()
            ->with("success", [__("toasts.institutions.updated")]);
    }
}
