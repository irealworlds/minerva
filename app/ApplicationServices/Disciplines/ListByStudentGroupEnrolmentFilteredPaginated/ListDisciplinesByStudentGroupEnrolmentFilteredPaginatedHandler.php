<?php

declare(strict_types=1);

namespace App\ApplicationServices\Disciplines\ListByStudentGroupEnrolmentFilteredPaginated;

use App\Core\Contracts\Cqrs\IQueryHandler;
use App\Core\Models\{Discipline, StudentDisciplineEnrolment};
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\ConnectionResolverInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\AbstractPaginator;
use InvalidArgumentException;

/**
 * @implements IQueryHandler<ListDisciplinesByStudentGroupEnrolmentFilteredPaginatedQuery, LengthAwarePaginator<Discipline>&AbstractPaginator<Discipline>>
 */
final readonly class ListDisciplinesByStudentGroupEnrolmentFilteredPaginatedHandler implements IQueryHandler
{
    public function __construct(private ConnectionResolverInterface $_db)
    {
    }

    /**
     * @inheritDoc
     * @throws InvalidArgumentException
     */
    public function __invoke(
        mixed $query,
    ): AbstractPaginator&LengthAwarePaginator {
        $queryBuilder = Discipline::query();

        // Add the student group enrolment filter
        $studentGroupEnrolmentKey = $query->studentGroupEnrolmentKey;
        $queryBuilder = $queryBuilder->whereHas(
            'studentEnrolments', // todo do not use magic strings
            static function (Builder $studentDisciplineEnrolmentQuery) use (
                $studentGroupEnrolmentKey,
            ) {
                return $studentDisciplineEnrolmentQuery->where(
                    (new StudentDisciplineEnrolment())
                        ->studentGroupEnrolment()
                        ->getForeignKeyName(),
                    $studentGroupEnrolmentKey,
                );
            },
        );

        // Add the search query filter
        if ($query->searchQuery->hasValue()) {
            if ($search = $query->searchQuery->value) {
                $queryBuilder = $queryBuilder->where(function (
                    Builder $disciplineQuery,
                ) use ($search): void {
                    $disciplineQuery
                        ->where(
                            $this->_db->connection()->raw('LOWER(name)'),
                            'like',
                            '%' . mb_strtolower($search, 'UTF-8') . '%',
                        )
                        ->orWhere(
                            $this->_db
                                ->connection()
                                ->raw('LOWER(abbreviation)'),
                            'like',
                            '%' . mb_strtolower($search, 'UTF-8') . '%',
                        );
                });
            }
        }

        return $queryBuilder->paginate(
            perPage: $query->pageSize,
            page: $query->page,
        );
    }
}
