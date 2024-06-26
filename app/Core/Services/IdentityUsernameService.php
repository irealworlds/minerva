<?php

declare(strict_types=1);

namespace App\Core\Services;

use Illuminate\Support\Str;
use RuntimeException;
use Transliterator;

final readonly class IdentityUsernameService
{
    /**
     * Normalize a username to a consistent format.
     *
     * @throws RuntimeException
     */
    public function normalizeUsername(string $username): string
    {
        $normalized = $this->transliterate($username);

        return Str::upper($normalized);
    }

    /**
     * @throws RuntimeException
     */
    protected function transliterate(string $username): string
    {
        $transliterator = Transliterator::createFromRules(
            ':: Any-Latin; :: Latin-ASCII; :: NFD; :: [:Nonspacing Mark:] Remove; :: Lower(); :: NFC;',
            Transliterator::FORWARD,
        );

        if (empty($transliterator)) {
            throw new RuntimeException(
                'Could not build transliterator instance.',
            );
        }

        $normalized = $transliterator->transliterate($username);

        if ($normalized === false) {
            throw new RuntimeException(
                "Could not transliterate username: $username.",
            );
        }

        return $normalized;
    }
}
