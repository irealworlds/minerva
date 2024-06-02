<?php

declare(strict_types=1);

namespace App\Core\Contracts\Services;

use DateInterval;
use DateTimeInterface;
use InvalidArgumentException;
use RuntimeException;

interface ISignedUrlGenerator
{
    /**
     * @param string[]|string $action
     * @param array<string, mixed> $parameters
     * @throws RuntimeException
     * @throws InvalidArgumentException
     */
    public function generateActionUri(
        array|string $action,
        array $parameters = [],
        DateInterval|DateTimeInterface|int|null $expiration = null,
        bool $absolute = true,
    ): string;
}
