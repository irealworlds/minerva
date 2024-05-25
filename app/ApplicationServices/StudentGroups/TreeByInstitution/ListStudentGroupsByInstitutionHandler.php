<?php

declare(strict_types=1);

namespace App\ApplicationServices\StudentGroups\TreeByInstitution;

use App\Core\Contracts\Cqrs\IQueryHandler;
use App\Core\Models\StudentGroup;
use Illuminate\Support\Enumerable;

/**
 * @implements IQueryHandler<ListStudentGroupsByInstitutionQuery, Enumerable<int, StudentGroup>>
 */
final readonly class ListStudentGroupsByInstitutionHandler implements IQueryHandler
{
    /**
     * @inheritDoc
     */
    public function __invoke(mixed $query): Enumerable
    {
        return $query->institution->groups()
            ->with('childGroups')
            ->get();
    }
}
