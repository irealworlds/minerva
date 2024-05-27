<?php

declare(strict_types=1);

namespace App\Core\Services;

use App\Core\Contracts\Services\IInertiaService;
use Inertia\ResponseFactory as Inertia;
use RuntimeException;

use function is_callable;

final readonly class InertiaService implements IInertiaService
{
    public function __construct(private Inertia $_inertia)
    {
    }

    /**
     * @inheritDoc
     *
     * @throws RuntimeException
     */
    public function addToastToCurrentRequest(
        string $type,
        string $message,
    ): void {
        $previouslySharedToasts = $this->_inertia->getShared('toasts', []);
        $previouslySharedToastsOfType = $this->_inertia->getShared(
            "toasts.$type",
            [],
        );

        if (is_callable($previouslySharedToastsOfType)) {
            $previouslySharedToastsOfType = $previouslySharedToastsOfType();
        }

        if (
            is_iterable($previouslySharedToasts) &&
            is_iterable($previouslySharedToastsOfType)
        ) {
            $this->_inertia->share('toasts', [
                ...$previouslySharedToasts,
                $type => [...$previouslySharedToastsOfType, $message],
            ]);
        } else {
            throw new RuntimeException(
                "Misconfigured shared Inertia data for toasts of type [$type].",
            );
        }
    }
}
