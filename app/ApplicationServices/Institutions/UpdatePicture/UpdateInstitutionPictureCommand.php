<?php

declare(strict_types=1);

namespace App\ApplicationServices\Institutions\UpdatePicture;

use App\Core\Contracts\Cqrs\ICommand;
use App\Core\Models\Institution;
use Illuminate\Http\UploadedFile;

final readonly class UpdateInstitutionPictureCommand implements ICommand
{
    public function __construct(
        public Institution $institution,
        public UploadedFile|null $newPicture
    ) {
    }
}
