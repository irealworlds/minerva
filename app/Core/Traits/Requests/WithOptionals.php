<?php

namespace App\Core\Traits\Requests;

use App\Core\Optional;

trait WithOptionals
{
    /**
     * Get a value from the request as an {@link Optional}.
     *
     * @template TValueType
     * @param string $key
     * @return Optional<TValueType>
     */
    public function optional(string $key): Optional
    {
        if ($this->has($key)) {
            return Optional::of($this->file($key) ?? $this->get($key));
        } else {
            return Optional::empty();
        }
    }
}
