<?php

declare(strict_types=1);

namespace App\ApplicationServices\EducatorInvitations\ListOutstandingForInstitution;

use App\Core\Contracts\Cqrs\IQueryHandler;
use App\Core\Models\EducatorInvitation;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Support\Enumerable;

/**
 * @implements IQueryHandler<ListOutstandingInvitationsForInstitutionQuery, Enumerable<int, EducatorInvitation>>
 */
final readonly class ListOutstandingInvitationsForInstitutionHandler implements
    IQueryHandler
{
    /**
     * @inheritDoc
     * @throws InvalidFormatException
     */
    public function __invoke(mixed $query): Enumerable
    {
        return $query->institution
            ->educatorInvitations()
            ->whereNull('responded_at')
            ->where('expired_at', '>', new Carbon())
            ->latest()
            ->get();
    }
}
