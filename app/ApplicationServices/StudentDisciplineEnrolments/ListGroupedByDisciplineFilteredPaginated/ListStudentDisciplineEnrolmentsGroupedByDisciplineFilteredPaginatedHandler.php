<?php

declare(strict_types=1);

namespace App\ApplicationServices\StudentDisciplineEnrolments\ListGroupedByDisciplineFilteredPaginated;

use App\Core\Contracts\Cqrs\IQueryHandler;
use App\Core\Models\{Discipline, StudentDisciplineEnrolment};
use App\Core\Optional;
use Illuminate\Contracts\Pagination\LengthAwarePaginator as ILengthAwarePaginator;
use Illuminate\Database\ConnectionResolverInterface;
use Illuminate\Pagination\{AbstractPaginator, LengthAwarePaginator};
use Illuminate\Support\{Collection, Enumerable};
use InvalidArgumentException;

/**
 * @implements IQueryHandler<ListStudentDisciplineEnrolmentsGroupedByDisciplineFilteredPaginatedQuery, ILengthAwarePaginator<array{
 *     0: string,
 *     1: Enumerable<int, StudentDisciplineEnrolment>
 * }>&AbstractPaginator<array{
 *     0: string,
 *     1: Enumerable<int, StudentDisciplineEnrolment>
 * }>>
 */
final readonly class ListStudentDisciplineEnrolmentsGroupedByDisciplineFilteredPaginatedHandler implements IQueryHandler
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
    ): AbstractPaginator&ILengthAwarePaginator {
        $pageSize = empty($query->pageSize)
            ? (new Discipline())->getPerPage()
            : $query->pageSize;

        // Fetch the disciplines included in the page.
        [$disciplineKeys, $total] = $this->getDisciplinesIncludedInPage(
            page: $query->page,
            pageSize: $pageSize,
            studentGroupEnrolmentKey: $query->studentGroupEnrolmentKey,
        );

        // Fetch the discipline enrolments for the disciplines.
        $disciplineEnrolments = StudentDisciplineEnrolment::query()->whereIn(
            (new StudentDisciplineEnrolment())
                ->discipline()
                ->getForeignKeyName(),
            $disciplineKeys,
        );
        $disciplineEnrolments = $disciplineEnrolments->get();

        // Group the discipline enrolments by discipline key.
        $items = new Collection();
        foreach ($disciplineKeys as $disciplineKey) {
            $items->push([
                $disciplineKey,
                $disciplineEnrolments
                    ->filter(
                        static fn (
                            StudentDisciplineEnrolment $disciplineEnrolment,
                        ) => $disciplineEnrolment->discipline->getKey() ===
                            $disciplineKey,
                    )
                    ->values(),
            ]);
        }

        // Build a length aware paginator and return it.
        /**
         * @var LengthAwarePaginator<array{
         *     0: string,
         *     1: Enumerable<int, StudentDisciplineEnrolment>
         * }> $paginator
         */
        $paginator = new LengthAwarePaginator(
            items: $items,
            total: $total,
            perPage: $pageSize,
            currentPage: $query->page,
        );

        return $paginator;
    }

    /**
     * @param Optional<mixed> $studentGroupEnrolmentKey
     * @return array{
     *     0: iterable<mixed>,
     *     1: int
     * }
     * @throws InvalidArgumentException
     */
    protected function getDisciplinesIncludedInPage(
        int $page,
        int $pageSize,
        Optional $studentGroupEnrolmentKey,
    ): array {
        $disciplineForeignKey = (new StudentDisciplineEnrolment())
            ->discipline()
            ->getForeignKeyName();

        // Start building the query.
        $query = $this->_db
            ->connection()
            ->table((new StudentDisciplineEnrolment())->getTable());

        // Add the student group enrolment key to the query if it has a value.
        if ($studentGroupEnrolmentKey->hasValue()) {
            $query = $query->where(
                (new StudentDisciplineEnrolment())
                    ->studentGroupEnrolment()
                    ->getForeignKeyName(),
                $studentGroupEnrolmentKey->value,
            );
        }

        // Group by the discipline foreign key and order by it.
        $query = $query
            ->groupBy($disciplineForeignKey)
            ->orderBy($disciplineForeignKey)
            ->select([$disciplineForeignKey]);

        // Run a count query to get the total number of disciplines.
        $total = $this->_db
            ->connection()
            ->table(clone $query, 'discipline_ids')
            ->count();

        $results = $query
            ->take($pageSize)
            ->skip(($page - 1) * $pageSize)
            ->get();

        // Paginate the query and return the results.
        return [
            $results->map(
                /**
                 * @param object{disciplineForeignKey: mixed} $row
                 */
                fn (mixed $row) => $row->{$disciplineForeignKey},
            ),
            $total,
        ];
    }
}
