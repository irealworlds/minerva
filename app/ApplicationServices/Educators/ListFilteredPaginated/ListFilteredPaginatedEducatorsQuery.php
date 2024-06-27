<?php

declare(strict_types=1);

namespace App\ApplicationServices\Educators\ListFilteredPaginated;

use App\Core\{EmptyOptional, Optional};
use App\Core\Contracts\Cqrs\IQuery;
use App\Core\Models\Educator;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\AbstractPaginator;

/**
 * @implements IQuery<LengthAwarePaginator<Educator> & AbstractPaginator<Educator>>
 */
final readonly class ListFilteredPaginatedEducatorsQuery implements IQuery
{
    /**
     * @param Optional<string> $searchQuery
     * @param Optional<iterable<mixed>> $associatedToInstitutionIds
     * @param Optional<iterable<mixed>> $notAssociatedToInstitutionIds
     */
    public function __construct(
        public int $pageSize,
        public int $page,
        public Optional $searchQuery = new EmptyOptional(),
        public Optional $associatedToInstitutionIds = new EmptyOptional(),
        public Optional $notAssociatedToInstitutionIds = new EmptyOptional(),
    ) {
    }
}
