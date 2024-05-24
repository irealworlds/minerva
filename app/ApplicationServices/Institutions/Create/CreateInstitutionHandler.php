<?php

namespace App\ApplicationServices\Institutions\Create;

use App\Core\Contracts\Cqrs\ICommandHandler;
use App\Core\Models\Institution;
use Throwable;

/**
 * @implements ICommandHandler<CreateInstitutionCommand>
 */
final readonly class CreateInstitutionHandler implements ICommandHandler
{
    /**
     * @inheritDoc
     * @throws Throwable
     */
    public function __invoke(mixed $command): void
    {
        /** @var Institution $institution */
        $institution = Institution::query()->make();
        $institution->id = $command->id;
        $institution->name = $command->name;
        $institution->website = $command->website;
        $institution->parent_institution_id = $command->parentId;
        $institution->saveOrFail();
    }
}
