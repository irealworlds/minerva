<?php

declare(strict_types=1);

namespace App\Core\Contracts\Cqrs;

use Illuminate\Contracts\Container\BindingResolutionException;
use ReflectionException;

interface ICommandBus
{
    /**
     * Handle a {@link $command command}.
     *
     * @throws ReflectionException
     * @throws BindingResolutionException
     */
    public function dispatch(ICommand $command): void;
}
