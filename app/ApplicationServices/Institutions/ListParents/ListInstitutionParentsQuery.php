<?php

declare(strict_types=1);

namespace App\ApplicationServices\Institutions\ListParents;

use App\Core\Contracts\Cqrs\IQuery;
use App\Core\Models\Institution;
use Illuminate\Support\Enumerable;

/**
 * @implements IQuery<Enumerable<int, Institution>> $parent
 */
final readonly class ListInstitutionParentsQuery implements IQuery
{
    public function __construct(public Institution $leaf)
    {
    }
}
