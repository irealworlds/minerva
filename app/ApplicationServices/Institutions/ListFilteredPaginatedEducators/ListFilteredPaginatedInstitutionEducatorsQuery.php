<?php

declare(strict_types=1);

namespace App\ApplicationServices\Institutions\ListFilteredPaginatedEducators;

use App\Core\Contracts\Cqrs\IQuery;
use App\Core\Models\InstitutionEducator;
use App\Core\Optional;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\AbstractPaginator;

/**
 * @implements IQuery<LengthAwarePaginator<InstitutionEducator> & AbstractPaginator<InstitutionEducator>>
 */
final readonly class ListFilteredPaginatedInstitutionEducatorsQuery implements
    IQuery
{
    /**
     * @param Optional<string> $searchQuery
     */
    public function __construct(
        public mixed $institutionId,
        public int $pageSize,
        public int $page,
        public Optional $searchQuery,
    ) {
    }
}
