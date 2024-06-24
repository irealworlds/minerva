<?php

declare(strict_types=1);

namespace App\ApplicationServices\StudentGroupDisciplineEducators\List;

use App\Core\Contracts\Cqrs\IQueryHandler;
use App\Core\Models\StudentGroupDisciplineEducator;
use Illuminate\Support\Enumerable;

/**
 * @implements IQueryHandler<ListStudentGroupDisciplineEducatorsQuery, Enumerable<int, StudentGroupDisciplineEducator>>
 */
final readonly class ListStudentGroupDisciplineEducatorsHandler implements
    IQueryHandler
{
    /**
     * @inheritDoc
     */
    public function __invoke(mixed $query): Enumerable
    {
        return StudentGroupDisciplineEducator::query()
            ->where(
                (new StudentGroupDisciplineEducator())
                    ->studentGroup()
                    ->getForeignKeyName(),
                $query->studentGroupKey,
            )
            ->where(
                (new StudentGroupDisciplineEducator())
                    ->educator()
                    ->getForeignKeyName(),
                $query->educatorKey,
            )
            ->get();
    }
}
