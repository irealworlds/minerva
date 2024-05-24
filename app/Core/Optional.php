<?php

namespace App\Core;

use Exception;
use Stringable;

/**
 * @template TValueType
 */
final readonly class Optional implements Stringable
{
    /**
     * @param TValueType $value
     * @param bool $hasValue
     */
    protected function __construct(
        protected mixed $value,
        protected bool $hasValue
    ) {
    }

    /**
     * @template TOptionalType
     * @param TOptionalType $value
     * @return Optional<TOptionalType>
     */
    public static function of(mixed $value): Optional
    {
        return new Optional($value, true);
    }

    /**
     * Get a new empty Optional object.
     *
     * @return Optional<void>
     */
    public static function empty(): Optional {
        return new Optional(null, false);
    }

    /**
     * Check whether this optional has a value set on it.
     *
     * @return bool
     */
    public function hasValue(): bool {
        return $this->hasValue;
    }

    /**
     * Get the value set on this object.
     *
     * @return TValueType
     * @throws Exception
     */
    public function getValue(): mixed {
        if (!$this->hasValue) {
            throw new Exception("Cannot read value on empty Optional type.");
        }

        return $this->value;
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        if ($this->hasValue) {
            return (string) $this->getValue();
        } else {
            return '<empty optional>';
        }
    }
}
