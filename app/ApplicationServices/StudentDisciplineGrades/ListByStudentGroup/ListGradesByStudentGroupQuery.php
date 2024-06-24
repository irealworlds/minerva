<?php

declare(strict_types=1);

namespace App\ApplicationServices\StudentDisciplineGrades\ListByStudentGroup;

use App\Core\Contracts\Cqrs\IQuery;
use App\Core\Models\StudentDisciplineGrade;
use Illuminate\Support\Enumerable;

/**
 * @implements IQuery<Enumerable<int, StudentDisciplineGrade>>
 */
final readonly class ListGradesByStudentGroupQuery implements IQuery
{
    function __construct(public mixed $studentGroupKey)
    {
    }
}
