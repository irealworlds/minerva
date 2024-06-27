<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Admin\StudentGroupEnrolments;

use App\ApplicationServices\Disciplines\FindByRouteKey\FindDisciplineByRouteKeyQuery;
use App\ApplicationServices\Educators\FindByRouteKey\FindEducatorByRouteKeyQuery;
use App\ApplicationServices\StudentDisciplineEnrolments\Create\CreateStudentDisciplineEnrolmentCommand;
use App\Core\Contracts\Cqrs\{ICommandBus, IQueryBus};
use App\Core\Models\{Discipline, Educator, StudentGroupEnrolment};
use App\Http\Web\Controllers\Controller;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Validation\ValidationException;
use ReflectionException;
use Spatie\RouteAttributes\Attributes\Post;

final readonly class StoreStudentGroupEnrolmentDisciplineController extends
    Controller
{
    public function __construct(
        private IQueryBus $_queryBus,
        private ICommandBus $_commandBus,
        private Redirector $_redirector,
    ) {
    }

    /**
     * @throws ValidationException
     * @throws BindingResolutionException
     * @throws ReflectionException
     */
    #[
        Post(
            '/Admin/StudentEnrolments/{enrolment}/Disciplines',
            name: 'admin.studentGroupEnrolments.read.disciplines.store',
        ),
    ]
    public function __invoke(
        StoreStudentGroupEnrolmentDisciplineRequest $request,
        StudentGroupEnrolment $enrolment,
    ): RedirectResponse {
        $educator = $this->extractEducatorFromRequest($request);
        $discipline = $this->extractDisciplineFromRequest($request);

        $this->_commandBus->dispatch(
            new CreateStudentDisciplineEnrolmentCommand(
                studentGroupEnrolmentKey: $enrolment->getKey(),
                disciplineKey: $discipline->getKey(),
                educatorKey: $educator->getKey(),
            ),
        );

        return $this->_redirector->action(
            ReadStudentGroupEnrolmentDisciplinesController::class,
            [
                'enrolment' => $enrolment->getRouteKey(),
            ],
        );
    }

    /**
     * @throws ValidationException
     */
    protected function extractEducatorFromRequest(
        StoreStudentGroupEnrolmentDisciplineRequest $request,
    ): Educator {
        $educator = $this->_queryBus->dispatch(
            new FindEducatorByRouteKeyQuery(routeKey: $request->educatorKey),
        );

        if (empty($educator)) {
            throw ValidationException::withMessages([
                'educatorKey' => __('validation.exists', [
                    'attribute' => 'educator',
                ]),
            ]);
        }

        return $educator;
    }

    /**
     * @throws ValidationException
     */
    protected function extractDisciplineFromRequest(
        StoreStudentGroupEnrolmentDisciplineRequest $request,
    ): Discipline {
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

        return $discipline;
    }
}
