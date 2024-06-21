<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Admin\EducatorDisciplines;

use App\ApplicationServices\Disciplines\FindByRouteKey\FindDisciplineByRouteKeyQuery;
use App\ApplicationServices\Educators\AddStudentGroupDisciplines\AddStudentGroupDisciplinesToEducatorsCommand;
use App\ApplicationServices\StudentGroups\FindByRouteKey\FindStudentGroupByRouteKeyQuery;
use App\Core\Contracts\Cqrs\{ICommandBus, IQueryBus};
use App\Core\Models\{Discipline, Educator, StudentGroup};
use App\Http\Web\Controllers\Controller;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\{Collection, Enumerable};
use Illuminate\Validation\ValidationException;
use ReflectionException;
use Spatie\RouteAttributes\Attributes\Post;

final readonly class StoreEducatorDisciplinesController extends Controller
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
            '/Admin/Educators/{educator}/Disciplines',
            name: 'admin.educators.disciplines.store',
        ),
    ]
    public function __invoke(
        StoreEducatorDisciplinesRequest $request,
        Educator $educator,
    ): RedirectResponse {
        // Extract entities from the request
        $studentGroup = $this->extractStudentGroup($request);
        $disciplines = $this->extractDisciplines($request);

        // Dispatch the command
        $this->_commandBus->dispatch(
            new AddStudentGroupDisciplinesToEducatorsCommand(
                educatorKey: $educator->getKey(),
                studentGroupKey: $studentGroup->getKey(),
                disciplineKeys: $disciplines->map(
                    static fn (Discipline $d) => $d->getKey(),
                ),
            ),
        );

        // Redirect back with a success toast
        return $this->_redirector
            ->back()
            ->with('success', [__('toasts.educators.disciplines.created')]);
    }

    /**
     * Extract the student group from the request.
     *
     * @throws ValidationException
     */
    protected function extractStudentGroup(
        StoreEducatorDisciplinesRequest $request,
    ): StudentGroup {
        $studentGroup = $this->_queryBus->dispatch(
            new FindStudentGroupByRouteKeyQuery(
                routeKey: $request->studentGroupKey,
            ),
        );
        if (empty($studentGroup)) {
            throw ValidationException::withMessages([
                'studentGroupKey' => __('validation.required', [
                    'attribute' => 'student group',
                ]),
            ]);
        }
        return $studentGroup;
    }

    /**
     * Extract the disciplines from the request.
     *
     * @return Enumerable<int, Discipline>
     * @throws ValidationException
     */
    protected function extractDisciplines(
        StoreEducatorDisciplinesRequest $request,
    ): Enumerable {
        $disciplines = new Collection();

        foreach ($request->disciplineKeys as $index => $disciplineKey) {
            $discipline = $this->_queryBus->dispatch(
                new FindDisciplineByRouteKeyQuery(routeKey: $disciplineKey),
            );

            if (empty($discipline)) {
                throw ValidationException::withMessages([
                    'disciplineKeys.' . $index => __('validation.required', [
                        'attribute' => 'discipline',
                    ]),
                ]);
            }

            $disciplines->push($discipline);
        }

        return $disciplines;
    }
}
