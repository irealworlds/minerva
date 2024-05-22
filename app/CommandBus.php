<?php

namespace App;

use App\Core\Contracts\Cqrs\ICommand;
use App\Core\Contracts\Cqrs\ICommandBus;
use Illuminate\Contracts\Container\Container;
use Illuminate\Database\ConnectionInterface;
use ReflectionClass;

final readonly class CommandBus implements ICommandBus
{
    function __construct(
        private Container $_container,
        private ConnectionInterface $_connection
    ) {
    }

    /** @inheritDoc */
    public function dispatch(ICommand $command): void
    {
        // resolve handler
        $reflection = new ReflectionClass($command);
        $handlerName = str_replace("Command", "Handler", $reflection->getShortName());
        $handlerName = str_replace($reflection->getShortName(), $handlerName, $reflection->getName());
        $handler = $this->_container->make($handlerName);

        // invoke handler
        $this->_connection->transaction(function () use ($handler, $command) {
            $handler($command);
        });
    }
}
