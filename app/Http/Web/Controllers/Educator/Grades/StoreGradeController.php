<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Educator\Grades;

use App\ApplicationServices\Disciplines\FindByRouteKey\FindDisciplineByRouteKeyQuery;
use App\ApplicationServices\StudentDisciplineEnrolments\FindByRouteKey\FindStudentDisciplineEnrolmentByRouteKeyQuery;
use App\ApplicationServices\StudentDisciplineGrades\Create\CreateStudentDisciplineGradeCommand;
use App\ApplicationServices\StudentGroups\FindByRouteKey\FindStudentGroupByRouteKeyQuery;
use App\Core\Contracts\Cqrs\ICommandBus;
use App\Core\Contracts\Cqrs\IQueryBus;
use App\Core\Models\Discipline;
use App\Core\Models\Educator;
use App\Core\Models\Identity;
use App\Core\Models\StudentDisciplineEnrolment;
use App\Core\Models\StudentGroup;
use App\Http\Web\Controllers\Controller;
use App\Http\Web\Controllers\Educator\Students\ManageStudentGradesController;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Factory;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Validation\ValidationException;
use ReflectionException;
use Spatie\RouteAttributes\Attributes\Post;

final readonly class StoreGradeController extends Controller
{
    public function __construct(
        private IQueryBus $_queryBus,
        private ICommandBus $_commandBus,
        private Factory $_authManager,
        private Redirector $_redirector,
    ) {
    }

    /**
     * Process a new grade request.
     *
     * @throws AuthenticationException
     * @throws AuthorizationException
     * @throws BindingResolutionException
     * @throws ReflectionException
     * @throws ValidationException
     */
    #[Post('/Educator/Grades', name: 'educator.grades.store')]
    public function __invoke(StoreGradeRequest $request): RedirectResponse
    {
        $educator = $this->getAuthenticatedEducatorProfile();

        // Parse the awarded at date
        try {
            $awardedAt = Carbon::parse($request->awardedAt);
        } catch (InvalidFormatException) {
            throw ValidationException::withMessages([
                'awardedAt' => __('validation.date', [
                    'attribute' => 'awarded at',
                ]),
            ]);
        }

        // Extract entities from the request
        [
            $studentDisciplineEnrolment,
            $studentGroup,
            $discipline,
        ] = $this->extractEntitiesFromRequest($request);

        // Create the grade
        $this->_commandBus->dispatch(
            new CreateStudentDisciplineGradeCommand(
                educatorKey: $educator->getKey(),
                studentKey: $studentDisciplineEnrolment->studentGroupEnrolment->studentRegistration->getKey(),
                studentGroupKey: $studentGroup->getKey(),
                disciplineKey: $discipline->getKey(),
                awardedPoints: $request->awardedPoints,
                maximumPoints: $request->maximumPoints,
                notes: $request->notes,
                awardedAt: $awardedAt,
            ),
        );

        return $this->_redirector
            ->action(ManageStudentGradesController::class, [
                'student' => $studentDisciplineEnrolment->studentGroupEnrolment->studentRegistration->getRouteKey(),
                'disciplineKey' => $discipline->getRouteKey(),
            ])
            ->with('success', [__('toasts.grades.created')]);
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
     *     0: StudentDisciplineEnrolment,
     *     1: StudentGroup,
     *     2: Discipline,
     * }
     * @throws ValidationException
     */
    protected function extractEntitiesFromRequest(
        StoreGradeRequest $request,
    ): array {
        // Get the student discipline enrolment
        $student = $this->_queryBus->dispatch(
            new FindStudentDisciplineEnrolmentByRouteKeyQuery(
                routeKey: $request->studentDisciplineEnrolmentKey,
            ),
        );

        if (empty($student)) {
            throw ValidationException::withMessages([
                'studentDisciplineEnrolmentKey' => __('validation.exists', [
                    'attribute' => 'student discipline enrolment',
                ]),
            ]);
        }

        // Get the student group
        $studentGroup = $this->_queryBus->dispatch(
            new FindStudentGroupByRouteKeyQuery(
                routeKey: $request->studentGroupKey,
            ),
        );

        if (empty($studentGroup)) {
            throw ValidationException::withMessages([
                'studentGroupKey' => __('validation.exists', [
                    'attribute' => 'student group',
                ]),
            ]);
        }

        // Get the discipline
        $discipline = $this->_queryBus->dispatch(
            new FindDisciplineByRouteKeyQuery(
                routeKey: $request->disciplineKey,
            ),
        );

        if (empty($discipline)) {
            throw ValidationException::withMessages([
                'disciplineKey' => __('validation.exists', [
                    'attribute' => 'discipline',
                ]),
            ]);
        }

        return [$student, $studentGroup, $discipline];
    }
}
