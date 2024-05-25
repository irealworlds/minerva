<?php

declare(strict_types=1);

namespace App\Http\ViewModels;

final readonly class StudentGroupTreeViewModel
{
    /**
     * @param iterable<StudentGroupTreeNodeViewModel> $items
     */
    public function __construct(
        public iterable $items
    ) {
    }
}
