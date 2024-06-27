<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Admin\StudentEnrolments;

use App\ApplicationServices\Disciplines\FindByRouteKey\FindDisciplineByRouteKeyQuery;
use App\ApplicationServices\Educators\FindByRouteKey\FindEducatorByRouteKeyQuery;
use App\ApplicationServices\StudentGroupEnrolments\Create\CreateStudentGroupEnrolmentCommand;
use App\ApplicationServices\StudentGroups\FindByRouteKey\FindStudentGroupByRouteKeyQuery;
use App\ApplicationServices\StudentRegistrations\Create\CreateStudentRegistrationCommand;
use App\ApplicationServices\StudentRegistrations\FindByRouteKey\FindStudentRegistrationByRouteKeyQuery;
use App\Core\Contracts\Cqrs\{ICommandBus, IQueryBus};
use App\Core\Dtos\{PersonalNameDto, StudentEnrolmentDisciplineDto};
use App\Core\Models\{Institution, StudentGroup, StudentRegistration};
use App\Http\Web\Controllers\Admin\Institutions\Management\ManageInstitutionStudentsController;
use App\Http\Web\Controllers\Controller;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\ConnectionResolverInterface;
use Illuminate\Database\Eloquent\{Model, ModelNotFoundException};
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\{Collection, Enumerable, Str};
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use ReflectionException;
use Spatie\RouteAttributes\Attributes\Post;
use Throwable;

final readonly class StoreStudentEnrolmentController extends Controller
{
    public function __construct(
        private Redirector $_redirector,
        private IQueryBus $_queryBus,
        private ICommandBus $_commandBus,
        private ConnectionResolverInterface $_db,
    ) {
    }

    /**
     * @throws ValidationException
     * @throws BindingResolutionException
     * @throws ReflectionException
     * @throws Throwable
     */
    #[
        Post(
            '/Admin/StudentEnrolments/Create',
            name: 'admin.student_enrolments.store',
        ),
    ]
    public function __invoke(
        StoreStudentEnrolmentRequest $request,
    ): RedirectResponse {
        // Get the student group from the request
        $studentGroup = $this->extractStudentGroup($request);

        // Get the disciplines from the request
        $disciplines = $this->extractStudiedDisciplines($request);

        $this->_db
            ->connection()
            ->transaction(function () use (
                $studentGroup,
                $disciplines,
                $request,
            ): void {
                // Get the student registration id from the request
                $studentRegistration = $this->extractOrCreateStudentRegistration(
                    $request,
                );

                // Create the student enrolment
                $this->_commandBus->dispatch(
                    new CreateStudentGroupEnrolmentCommand(
                        studentRegistrationKey: $studentRegistration->getKey(),
                        studentGroupKey: $studentGroup->getKey(),
                        disciplines: $disciplines,
                    ),
                );
            });

        // Redirect to the institution students management page
        return $this->buildRedirectResponse($studentGroup->parentInstitution);
    }

    /**
     * Get the student registration that is being enroled from the request.
     *
     * @throws BindingResolutionException
     * @throws ReflectionException
     * @throws ValidationException if the student registration does not exist
     * @throws ModelNotFoundException
     * @throws InvalidArgumentException
     */
    protected function extractOrCreateStudentRegistration(
        StoreStudentEnrolmentRequest $request,
    ): StudentRegistration {
        if ($request->studentKey) {
            $studentRegistration = $this->_queryBus->dispatch(
                new FindStudentRegistrationByRouteKeyQuery(
                    routeKey: $request->studentKey,
                ),
            );
            if (empty($studentRegistration)) {
                throw ValidationException::withMessages([
                    'studentKey' => __('validation.exists', [
                        'attribute' => 'student key',
                    ]),
                ]);
            }
        } elseif ($request->newIdentity) {
            $studentKey = Str::orderedUuid()->toString();
            $this->_commandBus->dispatch(
                new CreateStudentRegistrationCommand(
                    studentKey: $studentKey,
                    username: $request->newIdentity['idNumber'],
                    name: new PersonalNameDto(
                        prefix: empty($request->newIdentity['namePrefix'])
                            ? null
                            : $request->newIdentity['namePrefix'],
                        firstName: $request->newIdentity['firstName'],
                        middleNames: $request->newIdentity['middleNames'],
                        lastName: $request->newIdentity['lastName'],
                        suffix: empty($request->newIdentity['nameSuffix'])
                            ? null
                            : $request->newIdentity['nameSuffix'],
                    ),
                    email: $request->newIdentity['email'],
                ),
            );
            $studentRegistration = StudentRegistration::query()->findOrFail(
                $studentKey,
            );
        } else {
            throw new InvalidArgumentException(
                'Invalid student registration data.',
            );
        }
        return $studentRegistration;
    }

    /**
     * Get the student group the student is enrolling to from the request.
     *
     * @throws ValidationException if the student group does not exist
     */
    public function extractStudentGroup(
        StoreStudentEnrolmentRequest $request,
    ): StudentGroup {
        $studentGroup = $this->_queryBus->dispatch(
            new FindStudentGroupByRouteKeyQuery(
                routeKey: $request->studentGroupKey,
            ),
        );
        if (empty($studentGroup)) {
            throw ValidationException::withMessages([
                'studentGroupKey' => __('validation.exists', [
                    'attribute' => 'student group key',
                ]),
            ]);
        }
        return $studentGroup;
    }

    /**
     * Extract the disciplines the student is enrolling to from the request.
     *
     * @return Enumerable<int, StudentEnrolmentDisciplineDto>
     *
     * @throws ValidationException if any educator or discipline does not exist
     */
    public function extractStudiedDisciplines(
        StoreStudentEnrolmentRequest $request,
    ): Enumerable {
        $disciplines = new Collection();
        foreach ($request->disciplines as $index => $studiedDiscipline) {
            $educator = $this->_queryBus->dispatch(
                new FindEducatorByRouteKeyQuery(
                    routeKey: $studiedDiscipline['educatorKey'],
                ),
            );
            if (empty($educator)) {
                throw ValidationException::withMessages([
                    'disciplines.' . $index . '.educatorKey' => __(
                        'validation.exists',
                        [
                            'attribute' => 'educator',
                        ],
                    ),
                ]);
            }

            $discipline = $this->_queryBus->dispatch(
                new FindDisciplineByRouteKeyQuery(
                    routeKey: $studiedDiscipline['disciplineKey'],
                ),
            );
            if (empty($discipline)) {
                throw ValidationException::withMessages([
                    'disciplines.' . $index . '.disciplineKey' => __(
                        'validation.exists',
                        [
                            'attribute' => 'discipline',
                        ],
                    ),
                ]);
            }

            $disciplines->push(
                new StudentEnrolmentDisciplineDto(
                    disciplineKey: $discipline->getKey(),
                    educatorKey: $educator->getKey(),
                ),
            );
        }
        return $disciplines;
    }

    /**
     * Build a redirection response to the institution students management page.
     */
    public function buildRedirectResponse(
        Model|null $institution,
    ): RedirectResponse {
        // Build the redirection response
        $redirection =
            $institution instanceof Institution
                ? $this->_redirector->action(
                    ManageInstitutionStudentsController::class,
                    ['institution' => $institution->getRouteKey()],
                )
                : $this->_redirector->back();

        // Add a success toast to the redirection response
        return $redirection->with('success', [
            __('toasts.studentEnrolments.created'),
        ]);
    }
}
