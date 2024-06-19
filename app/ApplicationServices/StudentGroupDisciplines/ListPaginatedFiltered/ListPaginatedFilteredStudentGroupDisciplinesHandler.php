<?php

declare(strict_types=1);

namespace App\ApplicationServices\StudentGroupDisciplines\ListPaginatedFiltered;

use App\Core\Contracts\Cqrs\IQueryHandler;
use App\Core\Models\{StudentGroup, StudentGroupDiscipline};
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\AbstractPaginator;
use InvalidArgumentException;

/**
 * @implements IQueryHandler<ListPaginatedFilteredStudentGroupDisciplinesQuery, LengthAwarePaginator<StudentGroupDiscipline> & AbstractPaginator<StudentGroupDiscipline>>
 */
final readonly class ListPaginatedFilteredStudentGroupDisciplinesHandler implements IQueryHandler
{
    /**
     * @inheritDoc
     * @throws InvalidArgumentException
     */
    public function __invoke(
        mixed $query,
    ): AbstractPaginator&LengthAwarePaginator {
        $queryBuilder = StudentGroupDiscipline::query();

        // Filter by student group
        $studentGroupId = $query->studentGroupId;
        $queryBuilder = $queryBuilder->whereHas(
            (new StudentGroupDiscipline())->studentGroup()->getRelationName(),
            static function (Builder $sgQueryBuilder) use (
                $studentGroupId,
            ): void {
                $sgQueryBuilder->where(
                    (new StudentGroup())->getKeyName(),
                    $studentGroupId,
                );
            },
        );

        return $queryBuilder
            ->with([
                (new StudentGroupDiscipline())
                    ->studentGroup()
                    ->getRelationName(),
                (new StudentGroupDiscipline())->discipline()->getRelationName(),
            ])
            ->latest()
            ->paginate(perPage: $query->pageSize, page: $query->page);
    }
}
