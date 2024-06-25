<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Student\Enrolments;

use App\ApplicationServices\Disciplines\FindByRouteKey\FindDisciplineByRouteKeyQuery;
use App\ApplicationServices\StudentDisciplineGrades\ListFilteredPaginated\ListStudentDisciplineGradesFilteredPaginatedQuery;
use App\Core\Contracts\Cqrs\IQueryBus;
use App\Core\Models\{
    Discipline,
    Identity,
    StudentDisciplineGrade,
    StudentGroupEnrolment,
    StudentRegistration,
};
use App\Core\Optional;
use App\Http\Api\Assemblers\Dtos\Student\DisciplineDtoAssembler;
use App\Http\Web\ViewModels\Assemblers\GradeDetailsViewModelAssembler;
use App\Http\Web\ViewModels\Assemblers\Student\{
    GradeViewModelAssembler,
    StudentGroupEnrolmentViewModelAssembler,
};
use App\Http\Web\ViewModels\Student\GradeViewModel;
use Codestage\Authorization\Attributes\Authorize;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Factory as AuthManager;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\AbstractPaginator;
use Inertia\{Response as InertiaResponse, ResponseFactory};
use Spatie\RouteAttributes\Attributes\Get;

final readonly class ReadGradesController
{
    public function __construct(
        private IQueryBus $_queryBus,
        private AuthManager $_authManager,
        private ResponseFactory $_inertia,
        private StudentGroupEnrolmentViewModelAssembler $_studentGroupEnrolmentViewModelAssembler,
        private GradeViewModelAssembler $_gradeViewModelAssembler,
        private DisciplineDtoAssembler $_disciplineDtoAssembler,
        private GradeDetailsViewModelAssembler $_gradeDetailsViewModelAssembler,
    ) {
    }

    #[
        Get(
            '/Student/Enrolments/Details/{enrolment}/Grades/{grade?}',
            name: 'student.enrolments.read.grades',
        ),
    ]
    #[Authorize]
    public function __invoke(
        ReadGradesRequest $request,
        StudentGroupEnrolment $enrolment,
        StudentDisciplineGrade|null $grade = null,
    ): InertiaResponse {
        $filteredDiscipline = null;
        if ($request->filled('disciplineKey')) {
            $filteredDiscipline = $this->_queryBus->dispatch(
                new FindDisciplineByRouteKeyQuery(
                    routeKey: $request->string('disciplineKey')->toString(),
                ),
            );
        }

        $selectedGrade = null;
        if ($grade) {
            $selectedGrade = $this->_gradeDetailsViewModelAssembler->assemble(
                $grade,
            );
        }

        return $this->_inertia->render('Student/Enrolments/ReadGrades', [
            'enrolment' => $this->_studentGroupEnrolmentViewModelAssembler->assemble(
                $enrolment,
            ),
            'grades' => fn () => $this->getStudentGrades(
                $request,
                $enrolment,
                $filteredDiscipline,
            ),
            'filteredDiscipline' => $filteredDiscipline
                ? $this->_disciplineDtoAssembler->assemble($filteredDiscipline)
                : null,
            'selectedGrade' => $selectedGrade,
        ]);
    }

    /**
     * @throws AuthorizationException
     * @throws AuthenticationException
     * @return LengthAwarePaginator<GradeViewModel>&AbstractPaginator<GradeViewModel>
     */
    protected function getStudentGrades(
        ReadGradesRequest $request,
        StudentGroupEnrolment $enrolment,
        Discipline|null $disciplineFilter,
    ): AbstractPaginator&LengthAwarePaginator {
        $grades = $this->_queryBus->dispatch(
            new ListStudentDisciplineGradesFilteredPaginatedQuery(
                page: $request->integer('page'),
                pageSize: $request->integer('pageSize'),
                studentRegistrationKeys: Optional::of([
                    $this->getAuthenticatedStudentRegistration()->getKey(),
                ]),
                disciplineKeys: $disciplineFilter === null
                    ? Optional::empty()
                    : Optional::of([$disciplineFilter->getKey()]),
                studentGroupKeys: Optional::of([
                    $enrolment->studentGroup->getKey(),
                ]),
            ),
        );

        /** @var LengthAwarePaginator<GradeViewModel>&AbstractPaginator<GradeViewModel> $mappedGrades */
        $mappedGrades = $grades->setCollection(
            $grades
                ->getCollection()
                ->map(
                    fn (
                        StudentDisciplineGrade $grade,
                    ) => $this->_gradeViewModelAssembler->assemble($grade),
                ),
        );

        return $mappedGrades;
    }

    /**
     * @throws AuthenticationException
     * @throws AuthorizationException
     */
    protected function getAuthenticatedStudentRegistration(): StudentRegistration
    {
        /** @var Identity|null $identity */
        $identity = $this->_authManager->guard()->user();

        if (empty($identity)) {
            throw new AuthenticationException();
        }

        $studentRegistration = $identity->studentRegistration;

        if (empty($studentRegistration)) {
            throw new AuthorizationException();
        }

        return $studentRegistration;
    }
}
