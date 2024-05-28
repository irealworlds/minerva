<?php

declare(strict_types=1);

namespace App\Http\Web\ViewModels;

use App\Core\Models\StudentGroup;

final readonly class StudentGroupTreeNodeViewModel extends StudentGroupViewModel
{
    /**
     * @param iterable<object{id: string, type: 'institution'|'studentGroup', name: string}> $ancestors
     * @param iterable<mixed> $childrenIds
     */
    public function __construct(
        mixed $id,
        string $name,
        iterable $ancestors,
        iterable $childrenIds,
        string $createdAt,
        string $updatedAt,
        public StudentGroupTreeViewModel $children = new StudentGroupTreeViewModel(
            [],
        ),
    ) {
        parent::__construct(
            $id,
            $name,
            $ancestors,
            $childrenIds,
            $createdAt,
            $updatedAt,
        );
    }

    public static function fromModel(
        StudentGroup $model,
    ): StudentGroupTreeNodeViewModel {
        $children = $model->childGroups->map(
            static fn (StudentGroup $child) => self::fromModel($child),
        );

        $parentResult = parent::fromModel($model);
        return new self(
            id: $parentResult->id,
            name: $parentResult->name,
            ancestors: $parentResult->ancestors,
            childrenIds: $parentResult->childrenIds,
            createdAt: $parentResult->createdAt,
            updatedAt: $parentResult->updatedAt,
            children: new StudentGroupTreeViewModel($children),
        );
    }
}
