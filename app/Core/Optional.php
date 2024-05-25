<?php

declare(strict_types=1);

namespace App\Core;

use Override;
use Stringable;

/**
 * @template-covariant TValueType
 */
abstract readonly class Optional implements Stringable
{
    protected function __construct(private bool $_hasValue)
    {
    }

    /** @inheritDoc */
    #[Override]
    abstract public function __toString(): string;

    /**
     * Check whether this optional has a value set on it.
     *
     * @phpstan-assert-if-true FilledOptional<TValueType> $this
     */
    public function hasValue(): bool
    {
        return $this->_hasValue;
    }

    /**
     * Create a new optional with the given value.
     *
     * @template TOptionalType
     *
     * @param TOptionalType $value
     *
     * @return FilledOptional<TOptionalType>
     */
    public static function of(mixed $value): FilledOptional
    {
        return new FilledOptional($value);
    }

    /**
     * Get a new empty Optional object.
     *
     */
    public static function empty(): EmptyOptional
    {
        return new EmptyOptional();
    }
}
