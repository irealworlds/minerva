<?php

declare(strict_types=1);

namespace App\ApplicationServices\Educators\Create;

use App\ApplicationServices\Identities\Create\CreateIdentityCommand;
use App\ApplicationServices\Identities\FindByUsername\FindIdentityByUsernameQuery;
use App\Core\Contracts\Cqrs\{ICommandBus, ICommandHandler, IQueryBus};
use App\Core\Models\Educator;
use Illuminate\Database\ConnectionResolverInterface;
use RuntimeException;
use Throwable;

/**
 * @implements ICommandHandler<CreateEducatorProfileCommand>
 */
final readonly class CreateEducatorProfileHandler implements ICommandHandler
{
    public function __construct(
        private ICommandBus $_commandBus,
        private IQueryBus $_queryBus,
        private ConnectionResolverInterface $_db,
    ) {
    }

    /**
     * @inheritDoc
     * @throws Throwable
     */
    public function __invoke(mixed $command): void
    {
        $username = $command->username;
        $email = $command->email;
        $name = $command->name;
        $password = $command->password;

        $this->_db
            ->connection()
            ->transaction(function () use (
                $username,
                $email,
                $name,
                $password,
            ): void {
                $this->_commandBus->dispatch(
                    new CreateIdentityCommand(
                        username: $username,
                        name: $name,
                        email: $email,
                        password: $password,
                    ),
                );

                $identity = $this->_queryBus->dispatch(
                    new FindIdentityByUsernameQuery(username: $username),
                );

                if (empty($identity)) {
                    throw new RuntimeException('Could not create identity.');
                }

                /** @var Educator $educator */
                $educator = Educator::query()->make();
                $educator->identity_id = $identity->getKey();
                $educator->saveOrFail();
            });
    }
}
