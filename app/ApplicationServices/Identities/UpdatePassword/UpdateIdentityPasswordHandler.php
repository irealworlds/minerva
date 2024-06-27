<?php

declare(strict_types=1);

namespace App\ApplicationServices\Identities\UpdatePassword;

use App\Core\Contracts\Cqrs\ICommandHandler;
use Illuminate\Contracts\Hashing\Hasher;
use Throwable;

/**
 * @implements ICommandHandler<UpdateIdentityPasswordCommand>
 */
final readonly class UpdateIdentityPasswordHandler implements ICommandHandler
{
    public function __construct(private Hasher $_hasher)
    {
    }

    /**
     * @inheritDoc
     * @throws Throwable
     */
    public function __invoke(mixed $command): void
    {
        $command->identity->password = $this->_hasher->make($command->password);
        $command->identity->saveOrFail();
    }
}
