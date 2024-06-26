<?php

declare(strict_types=1);

namespace App\ApplicationServices\Identities\FindByUsername;

use App\Core\Contracts\Cqrs\IQueryHandler;
use App\Core\Models\Identity;
use App\Core\Services\IdentityUsernameService;
use RuntimeException;

/**
 * @implements IQueryHandler<FindIdentityByUsernameQuery, Identity|null>
 */
final readonly class FindIdentityByUsernameHandler implements IQueryHandler
{
    public function __construct(
        private IdentityUsernameService $_usernameService,
    ) {
    }

    /**
     * @inheritDoc
     * @throws RuntimeException
     */
    public function __invoke(mixed $query): Identity|null
    {
        return Identity::query()
            ->where(
                'normalized_username',
                $this->_usernameService->normalizeUsername($query->username),
            )
            ->first();
    }
}
