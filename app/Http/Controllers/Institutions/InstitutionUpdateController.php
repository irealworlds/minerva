<?php

namespace App\Http\Controllers\Institutions;

use App\ApplicationServices\Institutions\UpdateDetails\UpdateInstitutionDetailsCommand;
use App\ApplicationServices\Institutions\UpdatePicture\UpdateInstitutionPictureCommand;
use App\Core\Contracts\Cqrs\ICommandBus;
use App\Core\Models\Institution;
use App\Core\Optional;
use App\Http\Controllers\Controller;
use App\Http\Requests\Institutions\InstitutionPublicProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Routing\Redirector;
use Spatie\RouteAttributes\Attributes\Patch;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix("/Institutions/{institution}")]
final class InstitutionUpdateController extends Controller
{
    function __construct(
        private readonly ICommandBus $_commandBus,
        private readonly Redirector $_redirector
    ) {
    }

    #[Patch("/PublicProfile", name: "institutions.update.public")]
    public function updatePublicProfile(Institution $institution, InstitutionPublicProfileUpdateRequest $request): RedirectResponse
    {
        $this->_commandBus->dispatch(new UpdateInstitutionDetailsCommand(
            institution: $institution,
            name: $request->optional("name"),
            website: $request->optional("website")
        ));

        /** @var Optional<UploadedFile> $picture */
        $picture = $request->optional("picture");
        if ($picture->hasValue()) {
            $this->_commandBus->dispatch(new UpdateInstitutionPictureCommand(
                institution: $institution,
                newPicture: $picture->getValue()
            ));
        }

        return $this->_redirector->back();
    }
}
