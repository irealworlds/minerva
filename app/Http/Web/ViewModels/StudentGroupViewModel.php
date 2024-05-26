<?php

declare(strict_types=1);

namespace App\Http\Web\ViewModels;

use App\Core\Models\StudentGroup;

readonly class StudentGroupViewModel
{
    public function __construct(
        public mixed $id,
        public string $name
    ) {
    }

    /**
     * Create a new view model from the given model.
     */
    public static function fromModel(StudentGroup $studentGroup): StudentGroupViewModel
    {
        return new StudentGroupViewModel(
            id: $studentGroup->getRouteKey(),
            name: $studentGroup->name
        );
    }
}
