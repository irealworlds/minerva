<?php

declare(strict_types=1);

namespace App\ApplicationServices\Educators\ListStudentGroupDisciplinesFilteredPaginated;

use App\Core\Contracts\Cqrs\IQueryHandler;
use App\Core\Models\{StudentGroup, StudentGroupDisciplineEducator};
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\AbstractPaginator;
use InvalidArgumentException;

/**
 * @implements IQueryHandler<ListStudentGroupDisciplinesForEducatorFilteredPaginatedQuery, LengthAwarePaginator&AbstractPaginator<StudentGroupDisciplineEducator>>
 */
final readonly class ListStudentGroupDisciplinesForEducatorFilteredPaginatedHandler implements IQueryHandler
{
    /**
     * @inheritDoc
     * @return LengthAwarePaginator<StudentGroupDisciplineEducator>&AbstractPaginator<StudentGroupDisciplineEducator>
     * @throws InvalidArgumentException
     */
    public function __invoke(
        mixed $query,
    ): AbstractPaginator&LengthAwarePaginator {
        $queryBuilder = StudentGroupDisciplineEducator::query();

        // Filter by educator key
        $queryBuilder->where(
            (new StudentGroupDisciplineEducator())
                ->educator()
                ->getForeignKeyName(),
            $query->educatorKey,
        );

        // Filter by institution key
        if ($query->institutionKey->hasValue()) {
            $institutionKey = $query->institutionKey->value;
            $queryBuilder->whereHas(
                (new StudentGroupDisciplineEducator())
                    ->studentGroup()
                    ->getRelationName(),
                static function (Builder $sgQueryBuilder) use (
                    $institutionKey,
                ) {
                    return $sgQueryBuilder->where(
                        (new StudentGroup())
                            ->parentInstitution()
                            ->getForeignKeyName(),
                        $institutionKey,
                    );
                },
            );
        }

        // Return a paginated result
        return $queryBuilder->paginate(
            perPage: $query->pageSize,
            page: $query->page,
        );
    }
}
