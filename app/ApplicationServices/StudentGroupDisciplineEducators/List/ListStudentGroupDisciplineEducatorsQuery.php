<?php

declare(strict_types=1);

namespace App\ApplicationServices\StudentGroupDisciplineEducators\List;

use App\Core\Contracts\Cqrs\IQuery;
use App\Core\Models\StudentGroupDisciplineEducator;
use Illuminate\Support\Enumerable;

/**
 * @implements IQuery<Enumerable<int, StudentGroupDisciplineEducator>>
 */
final readonly class ListStudentGroupDisciplineEducatorsQuery implements IQuery
{
    function __construct(
        public mixed $educatorKey,
        public mixed $studentGroupKey,
    ) {
    }
}
