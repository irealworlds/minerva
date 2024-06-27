<?php

declare(strict_types=1);

namespace App\ApplicationServices\StudentRegistrations\Create;

use App\ApplicationServices\Identities\Create\CreateIdentityCommand;
use App\ApplicationServices\Identities\FindByUsername\FindIdentityByUsernameQuery;
use App\Core\Contracts\Cqrs\{ICommandBus, ICommandHandler, IQueryBus};
use App\Core\Models\StudentRegistration;
use Illuminate\Database\ConnectionResolverInterface;
use RuntimeException;

/**
 * @implements ICommandHandler<CreateStudentRegistrationCommand>
 */
final readonly class CreateStudentRegistrationHandler implements ICommandHandler
{
    public function __construct(
        private ICommandBus $_commandBus,
        private IQueryBus $_queryBus,
        private ConnectionResolverInterface $_db,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function __invoke(mixed $command): void
    {
        $studentRegistrationKey = $command->studentKey;
        $username = $command->username;
        $email = $command->email;
        $name = $command->name;
        $password = $command->password;

        $this->_db
            ->connection()
            ->transaction(function () use (
                $studentRegistrationKey,
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

                /** @var StudentRegistration $studentRegistration */
                $studentRegistration = StudentRegistration::query()->make();
                $studentRegistration->id = $studentRegistrationKey;
                $studentRegistration->identity_id = $identity->getKey();
                $studentRegistration->saveOrFail();
            });
    }
}
