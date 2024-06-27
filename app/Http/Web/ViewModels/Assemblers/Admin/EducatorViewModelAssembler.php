<?php

declare(strict_types=1);

namespace App\Http\Web\ViewModels\Assemblers\Admin;

use App\Core\Models\{Educator, Identity};
use App\Http\Web\ViewModels\Admin\EducatorViewModel;

final readonly class EducatorViewModelAssembler
{
    public function assemble(Educator $educator): EducatorViewModel
    {
        return new EducatorViewModel(
            key: $educator->getRouteKey(),
            fullName: $educator->identity->name->getFullName(),
            directoryName: $educator->identity->name->getDirectoryName(),
            email: $educator->identity->email,
            pictureUri: $educator->identity->getFirstMediaUrl(
                Identity::ProfilePictureMediaCollection,
            ),
            createdAt: $educator->created_at->toIso8601String(),
        );
    }
}
