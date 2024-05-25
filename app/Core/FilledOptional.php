<?php

namespace App\Core;

use JsonException;
use Override;
use Stringable;

/**
 * @template-covariant TValueType
 * @extends Optional<TValueType>
 */
final readonly class FilledOptional extends Optional
{
    /**
     * @param TValueType $value
     */
    function __construct(
        public mixed $value
    ) {
        parent::__construct(true);
    }

    /** @inheritDoc */
    #[Override]
    public function __toString(): string
    {
        $value = $this->value;
        if (is_bool($value) || is_float($value) || is_int($value) || is_resource($value) || is_string($value) || $value === null) {
            return strval($value);
        } else if ($value instanceof Stringable) {
            return $value->__toString();
        } else {
            try {
                return json_encode($value, JSON_THROW_ON_ERROR);
            } catch (JsonException) {
                return "<filled optional>";
            }
        }
    }
}
