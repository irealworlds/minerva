<?php

declare(strict_types=1);

namespace App\ApplicationServices\Educators\FindByEmail;

use App\Core\Contracts\Cqrs\IQueryHandler;
use App\Core\Models\Educator;
use Illuminate\Contracts\Database\Eloquent\Builder;

/**
 * @implements IQueryHandler<FindEducatorByEmailQuery, Educator|null>
 */
final readonly class FindEducatorByEmailHandler implements IQueryHandler
{
    /**
     * @inheritDoc
     */
    public function __invoke(mixed $query): Educator|null
    {
        $email = $query->email;
        return Educator::query()
            ->whereHas('identity', function (Builder $builder) use (
                $email,
            ): void {
                $builder->where('email', $email);
            })
            ->first();
    }
}
