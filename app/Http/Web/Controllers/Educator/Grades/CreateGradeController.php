<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Educator\Grades;

use App\ApplicationServices\Disciplines\FindByRouteKey\FindDisciplineByRouteKeyQuery;
use App\ApplicationServices\StudentDisciplineEnrolments\FindByRelatedEntities\FindStudentDisciplineEnrolmentByRelatedEntitiesQuery;
use App\ApplicationServices\StudentGroups\FindByRouteKey\FindStudentGroupByRouteKeyQuery;
use App\Core\Contracts\Cqrs\IQueryBus;
use App\Core\Models\Discipline;
use App\Core\Models\Educator;
use App\Core\Models\Identity;
use App\Core\Models\StudentDisciplineEnrolment;
use App\Core\Models\StudentGroup;
use App\Http\Api\Assemblers\Dtos\Educator\DisciplineDtoAssembler;
use App\Http\Api\Assemblers\Dtos\Educator\InstitutionDtoAssembler;
use App\Http\Api\Assemblers\Dtos\Educator\StudentDisciplineEnrolmentDtoAssembler;
use App\Http\Api\Assemblers\Dtos\Educator\StudentGroupDtoAssembler;
use App\Http\Web\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Factory as AuthManager;
use Inertia\Response as InertiaResponse;
use Inertia\ResponseFactory;
use Spatie\RouteAttributes\Attributes\Get;

final readonly class CreateGradeController extends Controller
{
    function __construct(
        private ResponseFactory $_inertia,
        private IQueryBus $_queryBus,
        private AuthManager $_authManager,
        private InstitutionDtoAssembler $_institutionDtoAssembler,
        private StudentGroupDtoAssembler $_studentGroupDtoAssembler,
        private DisciplineDtoAssembler $_disciplineDtoAssembler,
        private StudentDisciplineEnrolmentDtoAssembler $_studentDisciplineEnrolmentDtoAssembler,
    ) {
    }

    /**
     * @throws AuthorizationException
     * @throws AuthenticationException
     */
    #[Get('/Educator/Grades', name: 'educator.grades.create')]
    public function __invoke(CreateGradeRequest $request): InertiaResponse
    {
        [
            $intendedStudentGroup,
            $intendedDiscipline,
            $intendedStudentDisciplineEnrolment,
        ] = $this->extractIntendedTargetFromRequest($request);

        return $this->_inertia->render('Educator/Grades/Create', [
            'intendedInstitution' => empty($intendedStudentGroup)
                ? null
                : $this->_institutionDtoAssembler->assemble(
                    $intendedStudentGroup->parentInstitution,
                ),
            'intendedStudentGroup' => empty($intendedStudentGroup)
                ? null
                : $this->_studentGroupDtoAssembler->assemble(
                    $intendedStudentGroup,
                ),
            'intendedDiscipline' => empty($intendedDiscipline)
                ? null
                : $this->_disciplineDtoAssembler->assemble($intendedDiscipline),
            'intendedStudentEnrolment' => empty(
                $intendedStudentDisciplineEnrolment
            )
                ? null
                : $this->_studentDisciplineEnrolmentDtoAssembler->assemble(
                    $intendedStudentDisciplineEnrolment,
                ),
        ]);
    }

    /**
     * @throws AuthenticationException
     * @throws AuthorizationException
     */
    protected function getAuthenticatedEducatorProfile(): Educator
    {
        /** @var Identity|null $identity */
        $identity = $this->_authManager->guard()->user();

        if (empty($identity)) {
            throw new AuthenticationException();
        }

        $educator = $identity->educatorProfile;

        if (empty($educator)) {
            throw new AuthorizationException();
        }

        return $educator;
    }

    /**
     * @return array{
     *     0: StudentGroup|null,
     *     1: Discipline|null,
     *     2: StudentDisciplineEnrolment|null
     * }
     * @throws AuthenticationException
     * @throws AuthorizationException
     */
    protected function extractIntendedTargetFromRequest(
        CreateGradeRequest $request,
    ): array {
        $intendedStudentGroup = null;
        if ($request->filled('studentGroupKey')) {
            $intendedStudentGroup = $this->_queryBus->dispatch(
                new FindStudentGroupByRouteKeyQuery(
                    routeKey: $request->string('studentGroupKey')->toString(),
                ),
            );
        }

        $intendedDiscipline = null;
        if ($request->filled('disciplineKey')) {
            $intendedDiscipline = $this->_queryBus->dispatch(
                new FindDisciplineByRouteKeyQuery(
                    routeKey: $request->string('disciplineKey')->toString(),
                ),
            );
        }

        $intendedStudent = null;
        if (
            $request->filled('studentGroupKey') &&
            $request->filled('studentKey') &&
            $request->filled('disciplineKey')
        ) {
            $intendedStudent = $this->_queryBus->dispatch(
                new FindStudentDisciplineEnrolmentByRelatedEntitiesQuery(
                    studentGroupKey: $request
                        ->string('studentGroupKey')
                        ->toString(),
                    studentRegistrationKey: $request
                        ->string('studentKey')
                        ->toString(),
                    disciplineKey: $request
                        ->string('disciplineKey')
                        ->toString(),
                    educatorKey: $this->getAuthenticatedEducatorProfile()->getKey(),
                ),
            );
        }

        return [$intendedStudentGroup, $intendedDiscipline, $intendedStudent];
    }
}
