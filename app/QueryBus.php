<?php

namespace App;

use App\Core\Contracts\Cqrs\IQuery;
use App\Core\Contracts\Cqrs\IQueryBus;
use Illuminate\Contracts\Container\Container;
use ReflectionClass;

final readonly class QueryBus implements IQueryBus
{
    function __construct(
        private Container $_container
    ) {
    }

    /** @inheritDoc */
    public function dispatch(IQuery $query): mixed
    {
        // resolve handler
        $reflection = new ReflectionClass($query);
        $handlerName = str_replace("Query", "Handler", $reflection->getShortName());
        $handlerName = str_replace($reflection->getShortName(), $handlerName, $reflection->getName());
        $handler = $this->_container->make($handlerName);

        // invoke handler
        return $handler($query);
    }
}
