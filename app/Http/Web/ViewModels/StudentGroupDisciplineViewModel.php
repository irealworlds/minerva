<?php

declare(strict_types=1);

namespace App\Http\Web\ViewModels;

use App\Core\Models\Discipline;

final readonly class StudentGroupDisciplineViewModel
{
    public function __construct(public mixed $id, public string $name)
    {
    }

    public static function fromModel(
        Discipline $model,
    ): StudentGroupDisciplineViewModel {
        return new StudentGroupDisciplineViewModel(
            id: $model->getRouteKey(),
            name: $model->name,
        );
    }
}
