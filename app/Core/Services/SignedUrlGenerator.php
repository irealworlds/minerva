<?php

declare(strict_types=1);

namespace App\Core\Services;

use App\Core\Contracts\Services\ISignedUrlGenerator;
use Closure;
use DateInterval;
use DateTimeInterface;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\{Arr, InteractsWithTime};
use InvalidArgumentException;
use Override;
use RuntimeException;

use function array_key_exists;
use function call_user_func;
use function is_array;
use function is_callable;

/**
 * @todo remove this once Laravel adds support for this feature
 * @see https://github.com/laravel/framework/discussions/51668
 */
final readonly class SignedUrlGenerator implements ISignedUrlGenerator
{
    use InteractsWithTime;

    public function __construct(private UrlGenerator $_urlGenerator)
    {
    }

    #[Override]
    public function generateActionUri(
        array|string $action,
        array $parameters = [],
        DateInterval|DateTimeInterface|int|null $expiration = null,
        bool $absolute = true,
    ): string {
        /** @var array<string, mixed> $parameters */
        $parameters = Arr::wrap($parameters);
        $this->ensureSignedRouteParametersAreNotReserved($parameters);

        if ($expiration) {
            $parameters += [
                'expires' => $this->availableAt($expiration),
            ];
        }

        ksort($parameters);

        $key = call_user_func($this->getKeyResolver());

        return $this->_urlGenerator->action(
            action: $action,
            parameters: $parameters + [
                'signature' => hash_hmac(
                    algo: 'sha256',
                    data: $this->_urlGenerator->action(
                        $action,
                        $parameters,
                        $absolute,
                    ),
                    key: is_array($key) ? $key[0] : $key,
                ),
            ],
            absolute: $absolute,
        );
    }

    /**
     * @return callable(): (array{0: string}|string)
     * @throws RuntimeException
     */
    protected function getKeyResolver(): callable
    {
        $resolver = $this->getFromGenerator(function () {
            return $this->keyResolver; /* @phpstan-ignore property.protected */
        });

        if (!is_callable($resolver)) {
            throw new RuntimeException(
                'Unable to resolve the key used to sign the URL. Please provide a valid key resolver.',
            );
        }
        return $resolver;
    }

    /**
     * @param-closure-this \Illuminate\Routing\UrlGenerator $getter
     */
    protected function getFromGenerator(Closure $getter): mixed
    {
        return $getter->call($this->_urlGenerator);
    }

    /**
     * Ensure the given signed route parameters are not reserved.
     *
     * @param array<string, mixed> $parameters
     * @throws InvalidArgumentException
     */
    protected function ensureSignedRouteParametersAreNotReserved(
        array $parameters,
    ): void {
        if (array_key_exists('signature', $parameters)) {
            throw new InvalidArgumentException(
                '"Signature" is a reserved parameter when generating signed routes. Please rename your route parameter.',
            );
        }

        if (array_key_exists('expires', $parameters)) {
            throw new InvalidArgumentException(
                '"Expires" is a reserved parameter when generating signed routes. Please rename your route parameter.',
            );
        }
    }

    /**
     * Format the given controller action.
     *
     * @param string[]|string $action
     */
    protected function formatAction(array|string $action): string
    {
        if (is_array($action)) {
            $action = '\\' . implode('@', $action);
        }

        if (
            $this->_urlGenerator->getRootControllerNamespace() &&
            !str_starts_with($action, '\\')
        ) {
            return $this->_urlGenerator->getRootControllerNamespace() .
                '\\' .
                $action;
        }

        return trim($action, '\\');
    }
}
