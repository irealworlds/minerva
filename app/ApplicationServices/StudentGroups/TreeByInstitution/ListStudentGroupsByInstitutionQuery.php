<?php

declare(strict_types=1);

namespace App\ApplicationServices\StudentGroups\TreeByInstitution;

use App\Core\Contracts\Cqrs\IQuery;
use App\Core\Models\{
    Institution,
    StudentGroup};
use Illuminate\Support\Enumerable;

/**
 * @implements IQuery<Enumerable<int, StudentGroup>>
 */
final readonly class ListStudentGroupsByInstitutionQuery implements IQuery
{
    public function __construct(public Institution $institution)
    {
    }
}
