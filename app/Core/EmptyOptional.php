<?php

declare(strict_types=1);

namespace App\Core;

use Override;

/**
 * @extends Optional<never>
 */
final readonly class EmptyOptional extends Optional
{
    public function __construct()
    {
        parent::__construct(false);
    }

    /** @inheritDoc */
    #[Override]
    public function __toString(): string
    {
        return '<empty optional>';
    }
}
