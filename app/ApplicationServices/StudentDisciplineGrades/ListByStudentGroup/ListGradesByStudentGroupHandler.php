<?php

declare(strict_types=1);

namespace App\ApplicationServices\StudentDisciplineGrades\ListByStudentGroup;

use App\Core\Contracts\Cqrs\IQueryHandler;
use App\Core\Models\StudentDisciplineGrade;
use Illuminate\Support\Enumerable;

/**
 * @implements IQueryHandler<ListGradesByStudentGroupQuery, Enumerable<int, StudentDisciplineGrade>>
 */
final readonly class ListGradesByStudentGroupHandler implements IQueryHandler
{
    /**
     * @inheritDoc
     */
    public function __invoke(mixed $query): Enumerable
    {
        return StudentDisciplineGrade::query()
            ->where(
                (new StudentDisciplineGrade())
                    ->studentGroup()
                    ->getForeignKeyName(),
                $query->studentGroupKey,
            )
            ->get();
    }
}
