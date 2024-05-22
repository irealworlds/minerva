<?php

namespace App\Core\Contracts\Cqrs;

/**
 * @template TResult
 * @template TQuery of IQuery<TResult>
 */
interface IQueryHandler
{
    /**
     * Get a result for the {@link $query query}.
     *
     * @param IQuery $query
     * @return TResult
     */
    public function __invoke(mixed $query): mixed;
}
