<?php

declare(strict_types=1);

namespace App\Core\Contracts\Cqrs;

/**
 * @template TCommand of ICommand
 */
interface ICommandHandler
{
    /**
     * Handle the command.
     *
     * @param TCommand $command
     */
    public function __invoke(mixed $command): void;
}
