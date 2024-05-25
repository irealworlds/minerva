<?php

declare(strict_types=1);

namespace App\ApplicationServices\Institutions\ListFilteredPaginated;

use App\Core\Contracts\Cqrs\IQuery;
use App\Core\Models\Institution;
use App\Core\Optional;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\AbstractPaginator;

/**
 * @implements IQuery<LengthAwarePaginator<Institution> & AbstractPaginator<Institution>>
 */
final readonly class ListFilteredPaginatedInstitutionsQuery implements IQuery
{
    /**
     * @param Optional<string|null> $parentId
     * @param Optional<string> $searchQuery
     */
    public function __construct(
        public int $page,
        public int $pageSize,
        public Optional $parentId,
        public Optional $searchQuery
    ) {
    }
}
