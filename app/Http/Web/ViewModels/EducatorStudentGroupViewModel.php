<?php

declare(strict_types=1);

namespace App\Http\Web\ViewModels;

final readonly class EducatorStudentGroupViewModel
{
    /**
     * @param object{id: mixed, name: string} $institution
     * @param iterable<object{id: mixed, name: string}> $ancestors
     */
    function __construct(
        public mixed $id,
        public string $name,
        public object $institution,
        public iterable $ancestors,
        public int $disciplinesCount,
        public int $studentsCount,
        public string $teachingSince,
    ) {
    }
}
