<?php

declare(strict_types=1);

namespace App\ApplicationServices\Educators\AssociateToInstitution;

use App\ApplicationServices\InstitutionEducators\FindByKeys\FindInstitutionEducatorByKeysQuery;
use App\Core\Contracts\Cqrs\{ICommandHandler, IQueryBus};
use App\Core\Models\InstitutionEducator;
use Throwable;

/**
 * @implements ICommandHandler<AssociateEducatorToInstitutionCommand>
 */
final readonly class AssociateEducatorToInstitutionHandler implements
    ICommandHandler
{
    public function __construct(private IQueryBus $_queryBus)
    {
    }

    /**
     * @inheritDoc
     * @throws Throwable
     */
    public function __invoke(mixed $command): void
    {
        // Find existing association
        $existingAssociation = $this->_queryBus->dispatch(
            new FindInstitutionEducatorByKeysQuery(
                institutionKey: $command->institution->getKey(),
                educatorKey: $command->educator->getKey(),
            ),
        );

        // Update existing association or create new one
        if ($existingAssociation instanceof InstitutionEducator) {
            $existingAssociation->roles = [
                ...$existingAssociation->roles,
                ...$command->roles,
            ];
            $existingAssociation->saveOrFail();
        } else {
            $command->institution->educators()->attach($command->educator, [
                'roles' => $command->roles,
            ]);
        }
    }
}
