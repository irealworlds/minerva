<?php

namespace App\Core;

use Override;

/**
 * @extends Optional<never>
 */
final readonly class EmptyOptional extends Optional
{
    function __construct() {
        parent::__construct(false);
    }

    /** @inheritDoc */
    #[Override]
    public function __toString(): string
    {
        return "<empty optional>";
    }
}
