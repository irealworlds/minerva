<?php

declare(strict_types=1);

namespace App\Http\Web\ViewModels\Assemblers;

use App\Core\Models\StudentGroup;
use App\Core\Models\StudentGroupDisciplineEducator;
use App\Http\Web\ViewModels\EducatorStudentGroupViewModel;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Support\Enumerable;

final readonly class EducatorStudentGroupViewModelAssembler
{
    /**
     * @throws InvalidFormatException
     * @param Enumerable<int, StudentGroupDisciplineEducator> $disciplineAssociations
     */
    public function assemble(
        StudentGroup $studentGroup,
        Enumerable $disciplineAssociations,
    ): EducatorStudentGroupViewModel {
        $teachingSince = $disciplineAssociations->pluck('created_at')->min();
        if (!$teachingSince instanceof Carbon) {
            $teachingSince = new Carbon();
        }

        return new EducatorStudentGroupViewModel(
            id: $studentGroup->getRouteKey(),
            name: $studentGroup->name,
            institution: (object) [
                'id' => $studentGroup->parentInstitution->getRouteKey(),
                'name' => $studentGroup->parentInstitution->name,
            ],
            ancestors: $studentGroup->ancestors()->get()->map(
                static fn(StudentGroup $ancestor) => (object) [
                    'id' => $ancestor->getRouteKey(),
                    'name' => $ancestor->name,
                ],
            ),
            disciplinesCount: $disciplineAssociations->count(),
            studentsCount: $studentGroup->studentRegistrations()->count(), // todo count only students who actually attend classes
            teachingSince: $teachingSince->toISO8601String(),
        );
    }
}
