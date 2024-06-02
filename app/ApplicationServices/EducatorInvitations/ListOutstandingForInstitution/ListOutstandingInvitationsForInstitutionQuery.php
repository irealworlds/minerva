<?php

declare(strict_types=1);

namespace App\ApplicationServices\EducatorInvitations\ListOutstandingForInstitution;

use App\Core\Contracts\Cqrs\IQuery;
use App\Core\Models\{EducatorInvitation, Institution};
use Illuminate\Support\Enumerable;

/**
 * @implements IQuery<Enumerable<int, EducatorInvitation>>
 */
final readonly class ListOutstandingInvitationsForInstitutionQuery implements
    IQuery
{
    public function __construct(public Institution $institution)
    {
    }
}
