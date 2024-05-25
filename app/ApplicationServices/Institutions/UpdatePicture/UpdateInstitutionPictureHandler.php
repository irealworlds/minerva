<?php

namespace App\ApplicationServices\Institutions\UpdatePicture;

use App\Core\Contracts\Cqrs\ICommandHandler;
use App\Core\Models\Institution;
use Illuminate\Validation\ValidationException;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileCannotBeAdded;

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
                /** @throws FileCannotBeAdded  */
                $command->institution->addMedia($command->newPicture)
                    ->toMediaCollection(Institution::EmblemPictureMediaCollection);
            } catch (FileCannotBeAdded $e) {
                throw ValidationException::withMessages([
                    "newPicture" => $e->getMessage()
                ]);
            }
        }
    }
}
