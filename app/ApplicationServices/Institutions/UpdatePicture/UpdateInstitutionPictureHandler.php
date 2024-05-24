<?php

namespace App\ApplicationServices\Institutions\UpdatePicture;

use App\Core\Contracts\Cqrs\ICommandHandler;
use App\Core\Models\Institution;
use Illuminate\Validation\ValidationException;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

/**
 * @implements ICommandHandler<UpdateInstitutionPictureCommand>
 */
final readonly class UpdateInstitutionPictureHandler implements ICommandHandler
{
    /**
     * @inheritDoc
     * @throws ValidationException
     */
    public function __invoke(mixed $command): void
    {
        if ($command->newPicture === null) {
            $command->institution->clearMediaCollection(Institution::EmblemPictureMediaCollection);
        } else {
            try {
                $command->institution->addMedia($command->newPicture)
                    ->toMediaCollection(Institution::EmblemPictureMediaCollection);
            } catch (FileDoesNotExist|FileIsTooBig $e) {
                throw ValidationException::withMessages([
                    "newPicture" => $e->getMessage()
                ]);
            }
        }
    }
}
