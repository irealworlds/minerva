<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Student\Enrolments;

use App\ApplicationServices\StudentDisciplineGrades\List\ListStudentDisciplineGradesQuery;
use App\Core\Contracts\Cqrs\IQueryBus;
use App\Core\Enums\StudentEnrolmentActivityType;
use App\Core\Models\StudentDisciplineEnrolment;
use App\Core\Models\StudentDisciplineGrade;
use App\Core\Models\StudentGroupEnrolment;
use App\Core\Optional;
use App\Http\Web\ViewModels\Assemblers\Student\StudentGroupEnrolmentViewModelAssembler;
use App\Http\Web\ViewModels\Student\StudentEnrolmentActivityItemViewModel;
use Illuminate\Support\Collection;
use Illuminate\Support\Enumerable;
use Inertia\Response as InertiaResponse;
use Inertia\ResponseFactory;
use Spatie\RouteAttributes\Attributes\Get;

final readonly class ReadOverviewController
{
    function __construct(
        private IQueryBus $_queryBus,
        private ResponseFactory $_inertia,
        private StudentGroupEnrolmentViewModelAssembler $_studentGroupEnrolmentViewModelAssembler,
    ) {
    }

    #[
        Get(
            '/Student/Enrolments/Details/{enrolment}/Overview',
            name: 'student.enrolments.read.overview',
        ),
    ]
    public function __invoke(StudentGroupEnrolment $enrolment): InertiaResponse
    {
        return $this->_inertia->render('Student/Enrolments/ReadOverview', [
            'enrolment' => $this->_studentGroupEnrolmentViewModelAssembler->assemble(
                $enrolment,
            ),
            'activities' => fn() => $this->getActivityItems($enrolment),
            'statsData' => fn() => $this->getStatsData($enrolment),
        ]);
    }

    /**
     * @return Enumerable<int, StudentEnrolmentActivityItemViewModel>
     */
    public function getActivityItems(
        StudentGroupEnrolment $enrolment,
    ): Enumerable {
        $activityItems = new Collection([
            new StudentEnrolmentActivityItemViewModel(
                type: StudentEnrolmentActivityType::StudentGroupEnrolment,
                properties: (object) [],
                date: $enrolment->created_at,
            ),
        ]);

        $grades = $this->_queryBus->dispatch(
            new ListStudentDisciplineGradesQuery(
                studentRegistrationKeys: Optional::of([
                    $enrolment->studentRegistration->getKey(),
                ]),
                disciplineKeys: Optional::empty(),
                studentGroupKeys: Optional::of([
                    $enrolment->studentGroup->getKey(),
                ]),
            ),
        );
        foreach ($grades as $grade) {
            $activityItems->push(
                new StudentEnrolmentActivityItemViewModel(
                    type: StudentEnrolmentActivityType::NewGrade,
                    properties: (object) [
                        'awardedBy' => [
                            'name' => $grade->educator->identity->name->getFullName(),
                        ],
                        'awardedPoints' => $grade->awarded_points,
                        'maximumPoints' => $grade->maximum_points,
                        'notes' => $grade->notes,
                    ],
                    date: $grade->created_at,
                ),
            );
        }

        return $activityItems
            ->sortByDesc(
                static fn(
                    StudentEnrolmentActivityItemViewModel $activityItem,
                ) => $activityItem->date,
            )
            ->take(10)
            ->values();
    }

    /**
     * @return array{
     *     studentsCount: int,
     *     disciplineCount: int,
     *     educatorsCount: int,
     *     averageGrade: float|null
     * }
     */
    protected function getStatsData(StudentGroupEnrolment $enrolment): array
    {
        $uniqueDisciplineCounts = $enrolment
            ->disciplineEnrolments()
            ->select(
                (new StudentDisciplineEnrolment())
                    ->discipline()
                    ->getForeignKeyName(),
            )
            ->groupBy(
                (new StudentDisciplineEnrolment())
                    ->discipline()
                    ->getForeignKeyName(),
            )
            ->get();

        $uniqueEducatorsCount = $enrolment
            ->disciplineEnrolments()
            ->select(
                (new StudentDisciplineEnrolment())
                    ->educator()
                    ->getForeignKeyName(),
            )
            ->groupBy(
                (new StudentDisciplineEnrolment())
                    ->educator()
                    ->getForeignKeyName(),
            )
            ->get();

        $averageGrade = $this->_queryBus
            ->dispatch(
                new ListStudentDisciplineGradesQuery(
                    studentRegistrationKeys: Optional::of([
                        $enrolment->studentRegistration->getKey(),
                    ]),
                    disciplineKeys: Optional::empty(),
                    studentGroupKeys: Optional::of([
                        $enrolment->studentGroup->getKey(),
                    ]),
                ),
            )
            ->map(
                static fn(
                    StudentDisciplineGrade $grade,
                ) => $grade->awarded_points,
            )
            ->average();

        return [
            'studentsCount' => $enrolment->studentGroup
                ->studentRegistrations()
                ->count(),
            'disciplineCount' => $uniqueDisciplineCounts->count(),
            'educatorsCount' => $uniqueEducatorsCount->count(),
            'averageGrade' =>
                $averageGrade === null ? null : (float) $averageGrade,
        ];
    }
}
