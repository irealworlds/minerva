<?php

namespace App\Core\Contracts\Cqrs;

/**
 * @template TQuery of IQuery<TResult>
 * @template TResult
 */
interface IQueryHandler
{
    /**
     * Get a result for the {@link $query query}.
     *
     * @param TQuery $query
     * @return TResult
     */
    public function __invoke(mixed $query): mixed;
}
