<?php

declare(strict_types=1);

namespace App\Core;

use JsonException;
use Override;
use Stringable;

use function is_bool;
use function is_float;
use function is_int;
use function is_resource;
use function is_string;

use const JSON_THROW_ON_ERROR;

/**
 * @template-covariant TValueType
 *
 * @extends Optional<TValueType>
 */
final readonly class FilledOptional extends Optional
{
    /**
     * @param TValueType $value
     */
    public function __construct(public mixed $value)
    {
        parent::__construct(true);
    }

    /** @inheritDoc */
    #[Override]
    public function __toString(): string
    {
        $value = $this->value;
        if (
            is_bool($value) ||
            is_float($value) ||
            is_int($value) ||
            is_resource($value) ||
            is_string($value) ||
            $value === null
        ) {
            return (string) $value;
        } elseif ($value instanceof Stringable) {
            return $value->__toString();
        } else {
            try {
                return json_encode($value, JSON_THROW_ON_ERROR);
            } catch (JsonException) {
                return '<filled optional>';
            }
        }
    }
}
