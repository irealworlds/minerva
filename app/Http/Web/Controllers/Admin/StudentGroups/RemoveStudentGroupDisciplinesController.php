<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Admin\StudentGroups;

use App\ApplicationServices\StudentGroups\RemoveDiscipline\RemoveDisciplineFromStudentGroupCommand;
use App\Core\Contracts\Cqrs\ICommandBus;
use App\Core\Models\{Discipline, StudentGroup};
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use ReflectionException;
use Spatie\RouteAttributes\Attributes\Delete;

final readonly class RemoveStudentGroupDisciplinesController
{
    public function __construct(
        private ICommandBus $_commandBus,
        private Redirector $_redirector,
    ) {
    }

    /**
     * @throws BindingResolutionException
     * @throws ReflectionException
     */
    #[
        Delete(
            '/Admin/StudentGroups/{group}/Disciplines/{discipline}',
            name: 'admin.student_groups.disciplines.delete',
        ),
    ]
    public function __invoke(
        StudentGroup $group,
        Discipline $discipline,
    ): RedirectResponse {
        // Remove discipline from student group
        $this->_commandBus->dispatch(
            new RemoveDisciplineFromStudentGroupCommand(
                group: $group,
                discipline: $discipline,
            ),
        );

        // Redirect back with success message
        return $this->_redirector
            ->back()
            ->with('success', [__('toasts.studentGroups.discipline_removed')]);
    }
}
