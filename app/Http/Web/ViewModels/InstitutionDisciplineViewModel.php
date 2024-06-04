<?php

declare(strict_types=1);

namespace App\Http\Web\ViewModels;

use App\Core\Models\Discipline;

final readonly class InstitutionDisciplineViewModel
{
    public function __construct(
        public mixed $id,
        public string $name,
        public string|null $abbreviation,
        public string|null $addedAt,
    ) {
    }

    public static function fromModel(
        Discipline $discipline,
    ): InstitutionDisciplineViewModel {
        return new InstitutionDisciplineViewModel(
            id: $discipline->getRouteKey(),
            name: $discipline->name,
            abbreviation: $discipline->abbreviation,
            // todo do not use pivot directly
            addedAt: $discipline->pivot?->created_at?->toIso8601String(),
        );
    }
}
