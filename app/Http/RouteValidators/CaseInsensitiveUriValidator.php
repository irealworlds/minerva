<?php

declare(strict_types=1);

namespace App\Http\RouteValidators;

use Illuminate\Http\Request;
use Illuminate\Routing\Matching\ValidatorInterface;
use Illuminate\Routing\Route;
use RuntimeException;

final readonly class CaseInsensitiveUriValidator implements ValidatorInterface
{
    /**
     * @throws RuntimeException
     */
    public function matches(Route $route, Request $request): bool|int
    {
        $path = $request->path() === '/' ? '/' : '/' . $request->path();

        $replacePattern = preg_replace(
            pattern: '/$/',
            replacement: 'i',
            subject: $route->getCompiled()->getRegex(),
        );

        if (empty($replacePattern)) {
            throw new RuntimeException('Failed to build replace pattern.');
        }

        return preg_match(
            pattern: $replacePattern,
            subject: rawurldecode($path),
        );
    }
}
