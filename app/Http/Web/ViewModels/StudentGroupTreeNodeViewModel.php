<?php

declare(strict_types=1);

namespace App\Http\Web\ViewModels;

use App\Core\Models\StudentGroup;

final readonly class StudentGroupTreeNodeViewModel extends StudentGroupViewModel
{
    public function __construct(
        mixed $id,
        string $name,
        public StudentGroupTreeViewModel $children = new StudentGroupTreeViewModel([])
    ) {
        parent::__construct($id, $name);
    }

    public static function fromModel(StudentGroup $studentGroup): StudentGroupTreeNodeViewModel
    {
        $children = $studentGroup->childGroups
            ->map(static fn (StudentGroup $child) => self::fromModel($child));

        return new self(
            id: $studentGroup->getRouteKey(),
            name: $studentGroup->name,
            children: new StudentGroupTreeViewModel($children)
        );
    }
}
