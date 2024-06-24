<?php

declare(strict_types=1);

namespace App\ApplicationServices\StudentRegistrations\ListTaughtByEducatorFilteredPaginated;

use App\Core\Contracts\Cqrs\IQueryHandler;
use App\Core\Models\StudentDisciplineEnrolment;
use App\Core\Models\StudentGroupEnrolment;
use App\Core\Models\StudentRegistration;
use App\Core\Optional;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\ConnectionResolverInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\AbstractPaginator;
use InvalidArgumentException;

/**
 * @implements IQueryHandler<ListStudentsTaughtByEducatorFilteredPaginatedQuery,
 *     LengthAwarePaginator<StudentRegistration>&AbstractPaginator<StudentRegistration>>
 */
final readonly class ListStudentsTaughtByEducatorFilteredPaginatedHandler
    implements IQueryHandler
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
        $queryBuilder = StudentRegistration::query();

        // Add the search query filter
        $queryBuilder = $this->addSearchQueryFilter(
            $queryBuilder,
            $query->searchQuery,
        );

        $disciplineKey = $query->disciplineKey;
        $educatorKey = $query->educatorKey;
        $studentGroupKey = $query->studentGroupKey;

        $queryBuilder = $queryBuilder->whereHas(
            'studentGroupEnrolments', // todo do not use magic strings
            static function (Builder $studentGroupEnrolmentQuery) use (
                $educatorKey,
                $disciplineKey,
                $studentGroupKey,
            ) {
                // Add the student group filter
                if ($studentGroupKey->hasValue()) {
                    $studentGroupEnrolmentQuery = $studentGroupEnrolmentQuery->where(
                        (new StudentGroupEnrolment())
                            ->studentGroup()
                            ->getForeignKeyName(),
                        $studentGroupKey->value,
                    );
                }

                return $studentGroupEnrolmentQuery->whereHas(
                    'disciplineEnrolments', // todo do not use magic strings
                    function (Builder $disciplineEnrolmentQuery) use (
                        $disciplineKey,
                        $educatorKey,
                    ) {
                        // Add the discipline filter
                        if ($disciplineKey->hasValue()) {
                            $disciplineEnrolmentQuery = $disciplineEnrolmentQuery->where(
                                (new StudentDisciplineEnrolment())
                                    ->discipline()
                                    ->getForeignKeyName(),
                                $disciplineKey->value,
                            );
                        }

                        return $disciplineEnrolmentQuery->where(
                            (new StudentDisciplineEnrolment())
                                ->educator()
                                ->getForeignKeyName(),
                            $educatorKey,
                        );
                    },
                );
            },
        );

        // Return a paginated result
        return $queryBuilder
            ->latest()
            ->paginate(perPage: $query->pageSize, page: $query->page);
    }

    /**
     * @param Optional<string> $searchQuery
     * @param Builder<StudentRegistration> $queryBuilder
     * @return Builder<StudentRegistration>
     */
    public function addSearchQueryFilter(
        Builder $queryBuilder,
        Optional $searchQuery,
    ): Builder {
        if ($searchQuery->hasValue()) {
            if ($search = $searchQuery->value) {
                $queryBuilder = $queryBuilder->where(function (
                    Builder $searchQueryBuilder,
                ) use ($search): void {
                    $searchQueryBuilder->where(
                        $this->_db->connection()->raw('LOWER(name)'),
                        'like',
                        '%' . mb_strtolower($search, 'UTF-8') . '%',
                    );
                });
            }
        }

        return $queryBuilder;
    }
}
