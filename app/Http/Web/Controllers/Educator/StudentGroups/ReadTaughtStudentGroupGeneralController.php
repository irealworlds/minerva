<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Educator\StudentGroups;

use App\ApplicationServices\StudentDisciplineGrades\ListByStudentGroup\ListGradesByStudentGroupQuery;
use App\ApplicationServices\StudentGroupDisciplineEducators\List\ListStudentGroupDisciplineEducatorsQuery;
use App\Core\Contracts\Cqrs\IQueryBus;
use App\Core\Models\Educator;
use App\Core\Models\Identity;
use App\Core\Models\StudentDisciplineGrade;
use App\Core\Models\StudentGroup;
use App\Http\Web\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Auth\Factory;
use Inertia\Response as InertiaResponse;
use Inertia\ResponseFactory;
use Spatie\RouteAttributes\Attributes\Get;

final readonly class ReadTaughtStudentGroupGeneralController extends Controller
{
    function __construct(
        private ResponseFactory $_inertia,
        private IQueryBus $_queryBus,
        private Factory $_authManager,
    ) {
    }

    /**
     * @throws AuthorizationException
     */
    #[
        Get(
            '/Educator/StudentGroups/{studentGroup}/General',
            name: 'educator.studentGroups.read.general',
        ),
    ]
    public function __invoke(StudentGroup $studentGroup): InertiaResponse
    {
        $educator = $this->getCurrentEducatorProfile();

        if (empty($educator)) {
            throw new AuthorizationException();
        }

        $educatorDisciplineAssociations = $this->_queryBus->dispatch(
            new ListStudentGroupDisciplineEducatorsQuery(
                educatorKey: $educator->getKey(),
                studentGroupKey: $studentGroup->getKey(),
            ),
        );

        $grades = $this->_queryBus->dispatch(
            new ListGradesByStudentGroupQuery(
                studentGroupKey: $studentGroup->getKey(),
            ),
        );

        return $this->_inertia->render('Educator/StudentGroups/ReadGeneral', [
            'studentGroup' => [
                'id' => $studentGroup->getKey(),
                'name' => $studentGroup->name,
            ],
            'studentsCount' => $studentGroup->studentRegistrations()->count(),
            'disciplinesCount' => $educatorDisciplineAssociations->count(),
            'averageGrade' => $grades
                ->map(
                    static fn(
                        StudentDisciplineGrade $grade,
                    ) => $grade->awarded_points,
                )
                ->average(),
            'averageGradesCount' => $grades->count(),
        ]);
    }

    protected function getCurrentEducatorProfile(): Educator|null
    {
        /** @var Identity $identity */
        $identity = $this->_authManager->guard()->user();
        return $identity->educatorProfile;
    }
}
