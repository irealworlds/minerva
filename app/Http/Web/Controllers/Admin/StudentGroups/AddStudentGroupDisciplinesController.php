<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Admin\StudentGroups;

use App\ApplicationServices\Disciplines\FindByRouteKey\FindDisciplineByRouteKeyQuery;
use App\ApplicationServices\StudentGroups\AssociateDisciplines\AssociateDisciplinesToStudentGroupCommand;
use App\Core\Contracts\Cqrs\{ICommandBus, IQueryBus};
use App\Core\Models\StudentGroup;
use App\Http\Web\Requests\StudentGroups\AddStudentGroupDisciplinesRequest;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use ReflectionException;
use Spatie\RouteAttributes\Attributes\Post;

final readonly class AddStudentGroupDisciplinesController
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
            '/Admin/StudentGroups/{group}/Disciplines',
            name: 'admin.student_groups.disciplines.create',
        ),
    ]
    public function __invoke(
        StudentGroup $group,
        AddStudentGroupDisciplinesRequest $request,
    ): RedirectResponse {
        // Extract actual discipline ids from the request
        $disciplines = new Collection();
        foreach ($request->disciplineKeys as $index => $disciplineKey) {
            $discipline = $this->_queryBus->dispatch(
                new FindDisciplineByRouteKeyQuery($disciplineKey),
            );

            if (empty($discipline)) {
                throw ValidationException::withMessages([
                    "disciplineKeys.$index" => __('validation.exists', [
                        'attribute' => 'discipline key',
                    ]),
                ]);
            }

            $disciplines->push($discipline);
        }

        // Associate disciplines with the student group
        $this->_commandBus->dispatch(
            new AssociateDisciplinesToStudentGroupCommand(
                studentGroup: $group,
                disciplines: $disciplines,
            ),
        );

        // Redirect back with success message
        return $this->_redirector
            ->back()
            ->with('success', [
                __('toasts.studentGroups.discipline_associated'),
            ]);
    }
}
