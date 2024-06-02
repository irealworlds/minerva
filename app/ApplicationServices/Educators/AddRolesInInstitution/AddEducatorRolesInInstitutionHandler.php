<?php

declare(strict_types=1);

namespace App\ApplicationServices\Educators\AddRolesInInstitution;

use App\ApplicationServices\InstitutionEducators\FindByKeys\FindInstitutionEducatorByKeysQuery;
use App\Core\Contracts\Cqrs\{ICommandHandler, IQueryBus};
use App\Core\Models\InstitutionEducator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

/**
 * @implements ICommandHandler<AddEducatorRolesInInstitutionCommand>
 */
final readonly class AddEducatorRolesInInstitutionHandler implements
    ICommandHandler
{
    public function __construct(private IQueryBus $_queryBus)
    {
    }

    /**
     * @inheritDoc
     * @throws ModelNotFoundException
     * @throws Throwable
     */
    public function __invoke(mixed $command): void
    {
        $association = $this->_queryBus->dispatch(
            new FindInstitutionEducatorByKeysQuery(
                institutionKey: $command->institution->getKey(),
                educatorKey: $command->educator->getKey(),
            ),
        );

        if (empty($association)) {
            throw (new ModelNotFoundException())->setModel(
                InstitutionEducator::class,
            );
        }

        $association->roles = array_unique([
            ...$association->roles,
            ...$command->roles,
        ]);
        $association->saveOrFail();
    }
}
