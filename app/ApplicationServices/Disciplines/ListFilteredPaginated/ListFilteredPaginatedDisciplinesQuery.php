<?php

declare(strict_types=1);

namespace App\ApplicationServices\Disciplines\ListFilteredPaginated;

use App\Core\Contracts\Cqrs\IQuery;
use App\Core\Models\Discipline;
use App\Core\Optional;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\AbstractPaginator;

/**
 * @implements IQuery<LengthAwarePaginator<Discipline> & AbstractPaginator<Discipline>>
 */
final readonly class ListFilteredPaginatedDisciplinesQuery implements IQuery
{
    /**
     * @param Optional<string> $searchQuery
     * @param Optional<iterable<mixed>> $associatedToInstitutionIds
     * @param Optional<iterable<mixed>> $notAssociatedToInstitutionIds
     * @param Optional<iterable<mixed>> $associatedToStudentGroupIds
     * @param Optional<iterable<mixed>> $notAssociatedToStudentGroupIds
     */
    public function __construct(
        public int $page,
        public int $pageSize,
        public Optional $searchQuery,
        public Optional $associatedToInstitutionIds,
        public Optional $notAssociatedToInstitutionIds,
        public Optional $associatedToStudentGroupIds,
        public Optional $notAssociatedToStudentGroupIds,
    ) {
    }
}
