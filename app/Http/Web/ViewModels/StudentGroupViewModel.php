<?php

declare(strict_types=1);

namespace App\Http\Web\ViewModels;

use App\Core\Models\{Discipline, Institution, StudentGroup};
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

readonly class StudentGroupViewModel
{
    /**
     * @param iterable<object{id: string, type: 'institution'|'studentGroup', name: string}> $ancestors
     * @param iterable<StudentGroupDisciplineViewModel> $disciplines
     * @param iterable<mixed> $childrenIds
     */
    public function __construct(
        public mixed $id,
        public string $name,
        public iterable $ancestors,
        public iterable $childrenIds,
        public iterable $disciplines,
        public string $createdAt,
        public string $updatedAt,
    ) {
    }

    /**
     * Create a new view model from the given model.
     *
     * @throws InvalidArgumentException
     */
    public static function fromModel(StudentGroup $model): StudentGroupViewModel
    {
        /** @return iterable<object{id: string, type: 'institution'|'studentGroup', name: string}> */
        $getAncestors = function (Model $model) use (&$getAncestors): iterable {
            if (
                !(
                    $model instanceof Institution ||
                    $model instanceof StudentGroup
                )
            ) {
                throw new InvalidArgumentException(
                    'Received of type [' .
                        $model::class .
                        '] and could not determine parents.',
                );
            }

            if ($model->parent === null) {
                return [];
            }

            return [
                ...$getAncestors($model->parent),
                (object) [
                    'id' => $model->parent->getRouteKey(),
                    'type' =>
                        $model->parent instanceof Institution
                            ? 'institution'
                            : ($model->parent instanceof StudentGroup
                                ? 'studentGroup'
                                : 'unknown'),
                    'name' =>
                        $model->parent instanceof Institution
                            ? $model->parent->name
                            : ($model->parent instanceof StudentGroup
                                ? $model->parent->name
                                : $model::class),
                ],
            ];
        };

        /** @var iterable<object{id: string, type: 'institution'|'studentGroup', name: string}> $ancestors */
        $ancestors = $getAncestors($model);

        return new StudentGroupViewModel(
            id: $model->getRouteKey(),
            name: $model->name,
            ancestors: $ancestors,
            childrenIds: $model
                ->childGroups()
                ->select((new StudentGroup())->getKeyName())
                ->get()
                ->map(fn (StudentGroup $child) => $child->getKey()),
            disciplines: $model->disciplines->map(
                static fn (
                    Discipline $discipline,
                ) => StudentGroupDisciplineViewModel::fromModel($discipline),
            ),
            createdAt: $model->created_at->toIso8601String(),
            updatedAt: $model->updated_at->toIso8601String(),
        );
    }
}
