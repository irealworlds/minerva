<?php

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
     * @return void
     */
    public function __invoke(mixed $command): void;
}
