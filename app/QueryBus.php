<?php

declare(strict_types=1);

namespace App;

use App\Core\Contracts\Cqrs\{
    IQuery,
    IQueryBus};
use Illuminate\Contracts\Container\{
    BindingResolutionException,
    Container};
use ReflectionClass;
use function is_callable;

final readonly class QueryBus implements IQueryBus
{
    public function __construct(
        private Container $_container
    ) {
    }

    /**
     * @inheritDoc
     *
     * @throws BindingResolutionException
     */
    public function dispatch(IQuery $query): mixed
    {
        // resolve handler
        $reflection = new ReflectionClass($query);
        $handlerName = str_replace('Query', 'Handler', $reflection->getShortName());
        $handlerName = str_replace($reflection->getShortName(), $handlerName, $reflection->getName());
        $handler = $this->_container->make($handlerName);

        if (!is_callable($handler)) {
            throw new BindingResolutionException("Could not resolve [$handlerName] to a callable type.");
        }

        // invoke handler
        return $handler($query);
    }
}
