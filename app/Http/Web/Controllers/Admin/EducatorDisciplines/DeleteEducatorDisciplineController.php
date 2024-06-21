<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Admin\EducatorDisciplines;

use App\ApplicationServices\Educators\RemoveStudentGroupDiscipline\RemoveEducatorStudentGroupDisciplineCommand;
use App\Core\Contracts\Cqrs\ICommandBus;
use App\Core\Models\{Discipline, Educator, StudentGroup};
use App\Http\Web\Controllers\Controller;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use ReflectionException;
use Spatie\RouteAttributes\Attributes\Delete;

final readonly class DeleteEducatorDisciplineController extends Controller
{
    public function __construct(
        private ICommandBus $_commandBus,
        private Redirector $_redirector,
    ) {
    }

    /**
     * @throws ReflectionException
     * @throws BindingResolutionException
     */
    #[
        Delete(
            '/Admin/Educators/{educator}/StudentGroups/{studentGroup}/Disciplines/{discipline}',
            name: 'admin.educators.studentGroupDisciplines.delete',
        ),
    ]
    public function __invoke(
        Educator $educator,
        StudentGroup $studentGroup,
        Discipline $discipline,
    ): RedirectResponse {
        $this->_commandBus->dispatch(
            new RemoveEducatorStudentGroupDisciplineCommand(
                educatorKey: $educator->getKey(),
                studentGroupKey: $studentGroup->getKey(),
                disciplineKey: $discipline->getKey(),
            ),
        );

        return $this->_redirector
            ->back()
            ->with('success', [__('toasts.educators.disciplines.removed')]);
    }
}
