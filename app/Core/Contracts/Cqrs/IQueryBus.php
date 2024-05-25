<?php

declare(strict_types=1);

namespace App\Core\Contracts\Cqrs;

interface IQueryBus
{
    /**
     * Dispatch a query and return its result.
     *
     * @template TResult
     *
     * @param IQuery<TResult> $query
     *
     * @return TResult
     */
    public function dispatch(IQuery $query): mixed;
}
