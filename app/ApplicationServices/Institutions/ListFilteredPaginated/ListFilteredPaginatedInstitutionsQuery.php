<?php

namespace App\ApplicationServices\Institutions\ListFilteredPaginated;

use App\Core\Contracts\Cqrs\IQuery;
use App\Core\Models\Institution;
use App\Core\Optional;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * @implements IQuery<LengthAwarePaginator<Institution>>
 */
final readonly class ListFilteredPaginatedInstitutionsQuery implements IQuery
{
    public int $pageSize;
    public int $page;

    /** @var Optional<string> */
    public Optional $searchQuery;

    /** @var Optional<string|null> */
    public Optional $parentId;

    /**
     * @param int $page
     * @param int $pageSize
     * @param Optional<string|null>|null $parentId
     * @param Optional<string>|null $searchQuery
     */
    public function __construct(
        int $page,
        int $pageSize,
        Optional|null $parentId = null,
        Optional|null $searchQuery = null
    ) {
        $this->page = $page;
        $this->pageSize = $pageSize;

        if ($parentId === null) {
            $this->parentId = Optional::empty();
        } else {
            $this->parentId = $parentId;
        }

        if ($searchQuery === null) {
            $this->searchQuery = Optional::empty();
        } else {
            $this->searchQuery = $searchQuery;
        }
    }
}
