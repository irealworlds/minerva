<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\StudentGroups;

use App\ApplicationServices\StudentGroups\UpdateDetails\UpdateStudentGroupDetailsCommand;
use App\Core\Contracts\Cqrs\ICommandBus;
use App\Core\Enums\Permission;
use App\Core\Models\StudentGroup;
use App\Http\Web\Controllers\Controller;
use App\Http\Web\Requests\StudentGroups\StudentGroupUpdateRequest;
use Codestage\Authorization\Attributes\Authorize;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Validation\ValidationException;
use ReflectionException;
use Spatie\RouteAttributes\Attributes\Patch;

final readonly class UpdateStudentGroupController extends Controller
{
    public function __construct(
        private Redirector $_redirector,
        private ICommandBus $_commandBus,
    ) {
    }

    /**
     * @throws ReflectionException
     * @throws BindingResolutionException
     * @throws ValidationException
     */
    #[Patch('/StudentGroups/{group}', name: 'student_groups.update')]
    #[Authorize(permissions: Permission::StudentGroupUpdate)]
    public function __invoke(
        StudentGroup $group,
        StudentGroupUpdateRequest $request,
    ): RedirectResponse {
        $this->_commandBus->dispatch(
            new UpdateStudentGroupDetailsCommand(
                studentGroup: $group,
                name: $request->optionalString('name', false),
            ),
        );
        return $this->_redirector
            ->back()
            ->with('success', [__('toasts.studentGroups.updated')]);
    }
}
