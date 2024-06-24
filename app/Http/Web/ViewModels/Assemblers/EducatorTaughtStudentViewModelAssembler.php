<?php

declare(strict_types=1);

namespace App\Http\Web\ViewModels\Assemblers;

use App\Core\Models\StudentDisciplineGrade;
use App\Core\Models\StudentRegistration;
use App\Http\Web\ViewModels\EducatorTaughtStudentViewModel;
use Illuminate\Support\Enumerable;

final readonly class EducatorTaughtStudentViewModelAssembler
{
    /**
     * @param Enumerable<int, StudentDisciplineGrade> $grades
     */
    public function assemble(
        StudentRegistration $student,
        Enumerable $grades,
    ): EducatorTaughtStudentViewModel {
        return new EducatorTaughtStudentViewModel(
            studentRegistrationId: $student->getRouteKey(),
            studentName: $student->identity->name->getFullName(),
            currentAverage: $grades
                ->map(
                    static fn(
                        StudentDisciplineGrade $grade,
                    ) => $grade->awarded_points,
                )
                ->average(),
            gradesCount: $grades->count(),
        );
    }
}
