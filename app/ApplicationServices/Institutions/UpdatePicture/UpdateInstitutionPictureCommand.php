<?php

namespace App\ApplicationServices\Institutions\UpdatePicture;

use App\Core\Contracts\Cqrs\ICommand;
use App\Core\Models\Institution;
use SplFileInfo;

final readonly class UpdateInstitutionPictureCommand implements ICommand
{
    function __construct(
        public Institution $institution,
        public SplFileInfo|null $newPicture
    ) {
    }
}
