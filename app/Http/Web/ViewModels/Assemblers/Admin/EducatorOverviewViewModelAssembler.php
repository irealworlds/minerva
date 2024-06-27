<?php

declare(strict_types=1);

namespace App\Http\Web\ViewModels\Assemblers\Admin;

use App\Core\Models\{Educator, Identity};
use App\Http\Web\ViewModels\Admin\EducatorOverviewViewModel;

final readonly class EducatorOverviewViewModelAssembler
{
    public function assemble(Educator $educator): EducatorOverviewViewModel
    {
        return new EducatorOverviewViewModel(
            key: $educator->getRouteKey(),
            username: $educator->identity->username,
            fullName: $educator->identity->name->getFullName(),
            email: $educator->identity->email,
            pictureUri: $educator->identity->getFirstMediaUrl(
                Identity::ProfilePictureMediaCollection,
            ),
            createdAt: $educator->created_at->toIso8601String(),
        );
    }
}
