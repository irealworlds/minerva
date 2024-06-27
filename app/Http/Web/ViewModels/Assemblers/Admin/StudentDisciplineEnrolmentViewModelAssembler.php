<?php

declare(strict_types=1);

namespace App\Http\Web\ViewModels\Assemblers\Admin;

use App\ApplicationServices\StudentDisciplineGrades\List\ListStudentDisciplineGradesQuery;
use App\Core\Contracts\Cqrs\IQueryBus;
use App\Core\Models\{
    Discipline,
    Identity,
    StudentDisciplineEnrolment,
    StudentDisciplineGrade,
};
use App\Core\Optional;
use App\Http\Web\ViewModels\Admin\StudentDisciplineEnrolmentViewModel;
use Illuminate\Support\{Enumerable, ItemNotFoundException};
use InvalidArgumentException;

final readonly class StudentDisciplineEnrolmentViewModelAssembler
{
    public function __construct(private IQueryBus $_queryBus)
    {
    }

    /**
     * @param Enumerable<int, StudentDisciplineEnrolment> $enrolments
     * @throws InvalidArgumentException
     * @throws ItemNotFoundException
     */
    public function assemble(
        Enumerable $enrolments,
    ): StudentDisciplineEnrolmentViewModel {
        if ($enrolments->isEmpty()) {
            throw new InvalidArgumentException('No enrolments provided');
        }
        $discipline = $enrolments->firstOrFail()->discipline;

        if (
            $enrolments->some(
                static fn (
                    StudentDisciplineEnrolment $enrolment,
                ) => $enrolment->discipline->getKey() !== $discipline->getKey(),
            )
        ) {
            throw new InvalidArgumentException(
                'Enrolments must be for the same discipline',
            );
        }

        $grades = $this->getGrades($enrolments, $discipline);

        return new StudentDisciplineEnrolmentViewModel(
            enrolmentKeys: $enrolments->map(
                static fn (
                    StudentDisciplineEnrolment $enrolment,
                ) => $enrolment->getRouteKey(),
            ),
            disciplineKey: $discipline->getRouteKey(),
            disciplineName: $discipline->name,
            disciplineAbbreviation: $discipline->abbreviation,
            disciplinePictureUri: 'https://ui-avatars.com/api/?name=' .
                urlencode($discipline->name) .
                '&background=random&size=128',
            educators: $enrolments
                ->map(
                    static fn (
                        StudentDisciplineEnrolment $enrolment,
                    ) => (object) [
                        'key' => $enrolment->getRouteKey(),
                        'name' => $enrolment->educator->identity->name->getFullName(),
                        'pictureUri' => $enrolment->educator->identity->getFirstMediaUrl(
                            Identity::ProfilePictureMediaCollection,
                        ),
                    ],
                )
                ->values(),
            gradesCount: $grades->count(),
            averageGrade: $grades
                ->map(
                    static fn (
                        StudentDisciplineGrade $grade,
                    ) => $grade->awarded_points,
                )
                ->average(),
        );
    }

    /**
     * @param Enumerable<int, StudentDisciplineEnrolment> $enrolments
     * @return Enumerable<int, StudentDisciplineGrade>
     */
    public function getGrades(
        Enumerable $enrolments,
        Discipline $discipline,
    ): Enumerable {
        return $this->_queryBus->dispatch(
            new ListStudentDisciplineGradesQuery(
                studentRegistrationKeys: Optional::of([
                    $enrolments
                        ->map(static function (
                            StudentDisciplineEnrolment $disciplineEnrolment,
                        ) {
                            return $disciplineEnrolment->studentGroupEnrolment->studentRegistration->getKey();
                        })
                        ->unique()
                        ->values()
                        ->toArray(),
                ]),
                disciplineKeys: Optional::of([$discipline->getKey()]),
                studentGroupKeys: Optional::of(
                    $enrolments
                        ->map(static function (
                            StudentDisciplineEnrolment $disciplineEnrolment,
                        ) {
                            return $disciplineEnrolment->studentGroupEnrolment->studentGroup->getKey();
                        })
                        ->unique()
                        ->values()
                        ->toArray(),
                ),
            ),
        );
    }
}
