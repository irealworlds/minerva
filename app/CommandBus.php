<?php

declare(strict_types=1);

namespace App;

use App\Core\Contracts\Cqrs\{
    ICommand,
    ICommandBus};
use Illuminate\Contracts\Container\{
    BindingResolutionException,
    Container};
use Illuminate\Database\ConnectionInterface;
use ReflectionClass;
use function is_callable;

final readonly class CommandBus implements ICommandBus
{
    public function __construct(
        private Container $_container,
        private ConnectionInterface $_connection
    ) {
    }

    /** @inheritDoc */
    public function dispatch(ICommand $command): void
    {
        // resolve handler
        $reflection = new ReflectionClass($command);
        $handlerName = str_replace('Command', 'Handler', $reflection->getShortName());
        $handlerName = str_replace($reflection->getShortName(), $handlerName, $reflection->getName());
        $handler = $this->_container->make($handlerName);

        // invoke handler
        $this->_connection->transaction(static function () use ($handlerName, $handler, $command): void {
            if (!is_callable($handler)) {
                throw new BindingResolutionException("Could not resolve [$handlerName] to a callable type.");
            }

            $handler($command);
        });
    }
}
