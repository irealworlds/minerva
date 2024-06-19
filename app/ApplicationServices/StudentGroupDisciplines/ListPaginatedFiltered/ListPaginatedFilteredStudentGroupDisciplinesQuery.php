<?php

declare(strict_types=1);

namespace App\ApplicationServices\StudentGroupDisciplines\ListPaginatedFiltered;

use App\Core\Contracts\Cqrs\IQuery;
use App\Core\Models\StudentGroupDiscipline;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\AbstractPaginator;

/**
 * @implements IQuery<LengthAwarePaginator<StudentGroupDiscipline> & AbstractPaginator<StudentGroupDiscipline>>
 */
final readonly class ListPaginatedFilteredStudentGroupDisciplinesQuery implements IQuery
{
    public function __construct(
        public mixed $studentGroupId,
        public int $page,
        public int $pageSize,
    ) {
    }
}
