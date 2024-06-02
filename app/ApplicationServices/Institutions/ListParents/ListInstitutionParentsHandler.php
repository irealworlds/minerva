<?php

declare(strict_types=1);

namespace App\ApplicationServices\Institutions\ListParents;

use App\Core\Contracts\Cqrs\IQueryHandler;
use App\Core\Models\Institution;
use Illuminate\Support\{Collection, Enumerable};

/**
 * @implements IQueryHandler<ListInstitutionParentsQuery, Enumerable<int, Institution>>
 */
final readonly class ListInstitutionParentsHandler implements IQueryHandler
{
    /**
     * @inheritDoc
     */
    public function __invoke(mixed $query): Enumerable
    {
        $parents = new Collection();
        $current = $query->leaf;
        do {
            $current = $current->parent;
            if ($current instanceof Institution) {
                $parents->push($current);
            }
        } while (!empty($current));

        return $parents;
    }
}
