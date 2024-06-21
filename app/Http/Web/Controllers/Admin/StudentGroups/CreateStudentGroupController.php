<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Admin\StudentGroups;

use App\ApplicationServices\StudentGroups\Create\CreateStudentGroupCommand;
use App\Core\Contracts\Cqrs\ICommandBus;
use App\Core\Contracts\Services\IInertiaService;
use App\Core\Enums\Permission;
use App\Core\Models\{Institution, StudentGroup};
use App\Http\Web\Controllers\Admin\Institutions\InstitutionReadController;
use App\Http\Web\Requests\StudentGroups\StudentGroupCreateRequest;
use App\Http\Web\ViewModels\{
    Assemblers\InstitutionViewModelAssembler,
    StudentGroupViewModel,
};
use Codestage\Authorization\Attributes\Authorize;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\{RedirectResponse, Request};
use Illuminate\Routing\Redirector;
use Illuminate\Validation\ValidationException;
use Inertia\{Response as InertiaResponse, ResponseFactory};
use InvalidArgumentException;
use ReflectionException;
use RuntimeException;
use Spatie\RouteAttributes\Attributes\{Get, Post};

final readonly class CreateStudentGroupController
{
    public function __construct(
        private ICommandBus $_commandBus,
        private IInertiaService $_inertiaService,
        private ResponseFactory $_inertia,
        private Redirector $_redirector,
        private UrlGenerator $_urlGenerator,
        private InstitutionViewModelAssembler $_institutionViewModelAssembler,
    ) {
    }

    /**
     * Handle the incoming request.
     *
     * @throws InvalidArgumentException
     */
    #[Get('/Admin/StudentGroups/Create', name: 'admin.student_groups.create')]
    #[Authorize(permissions: Permission::StudentGroupCreate)]
    public function create(Request $request): InertiaResponse
    {
        // Determine the parent for the new group
        $parent = null;
        if (
            $request->query->has('parentId') ||
            $request->query->has('parentType')
        ) {
            try {
                if (
                    !strcasecmp(
                        $request->query->getString('parentType'),
                        'institution',
                    )
                ) {
                    $parent = Institution::query()
                        ->where(
                            (new Institution())->getRouteKeyName(),
                            $request->query->getString('parentId'),
                        )
                        ->firstOrFail();
                } elseif (
                    !strcasecmp(
                        $request->query->getString('parentType'),
                        'studentGroup',
                    )
                ) {
                    $parent = StudentGroup::query()
                        ->where(
                            (new StudentGroup())->getRouteKeyName(),
                            $request->query->getString('parentId'),
                        )
                        ->firstOrFail();
                } else {
                    throw new ModelNotFoundException();
                }
            } catch (ModelNotFoundException) {
                $warningMessage = __(
                    'Could not automatically determine parent.',
                );
                if (is_iterable($warningMessage)) {
                    $warningMessage =
                        'Could not automatically determine parent.';
                }
                $this->_inertiaService->addToastToCurrentRequest(
                    'warning',
                    $warningMessage,
                );
            }
        }

        return $this->_inertia->render('Admin/StudentGroups/Create', [
            'initialParentType' =>
                $parent instanceof Institution
                    ? 'institution'
                    : ($parent instanceof StudentGroup
                        ? 'studentGroup'
                        : null),
            'initialParent' =>
                $parent instanceof Institution
                    ? $this->_institutionViewModelAssembler->assemble($parent)
                    : ($parent instanceof StudentGroup
                        ? StudentGroupViewModel::fromModel($parent)
                        : null),
        ]);
    }

    /**
     * Handle the incoming request.
     *
     * @throws ValidationException
     * @throws BindingResolutionException
     * @throws ReflectionException
     * @throws RuntimeException
     */
    #[Post('/StudentGroups/Create', name: 'student_groups.store')]
    #[Authorize(permissions: Permission::StudentGroupCreate)]
    public function store(StudentGroupCreateRequest $request): RedirectResponse
    {
        // Validate and extract the parent from the request
        try {
            if (!strcasecmp($request->parentType, 'institution')) {
                $parent = Institution::query()
                    ->where(
                        (new Institution())->getRouteKeyName(),
                        $request->parentId,
                    )
                    ->firstOrFail();
            } elseif (!strcasecmp($request->parentType, 'studentGroup')) {
                $parent = StudentGroup::query()
                    ->where(
                        (new StudentGroup())->getRouteKeyName(),
                        $request->parentId,
                    )
                    ->firstOrFail();
            } else {
                throw new ModelNotFoundException();
            }
        } catch (ModelNotFoundException) {
            $validationMessage = __('validation.exists', [
                'attribute' => 'parent id',
            ]);
            if (is_iterable($validationMessage)) {
                $validationMessage = 'Parent id not found.';
            }
            throw ValidationException::withMessages([
                'parentId' => $validationMessage,
            ]);
        }

        // Generate a new id for the group
        $id = (new StudentGroup())->newUniqueId();

        // Create a new student group entity
        $this->_commandBus->dispatch(
            new CreateStudentGroupCommand(
                id: $id,
                name: $request->name,
                parent: $parent,
            ),
        );

        // Figure out where the user should be redirected
        if ($parent instanceof Institution) {
            $redirectTo = $this->_urlGenerator->action(
                InstitutionReadController::class,
                [
                    'institution' => $parent,
                ],
            );
        } else {
            $parentInstitution = $parent->parent;
            while (!($parentInstitution instanceof Institution)) {
                if ($parentInstitution instanceof StudentGroup) {
                    $parentInstitution = $parentInstitution->parent;
                } else {
                    throw new RuntimeException(
                        'Unexpected parent of type [' .
                            $parentInstitution::class .
                            '] encountered in the parent tree of [' .
                            $parent::class .
                            '] with id [' .
                            $parent->getKey() .
                            '].',
                    );
                }
            }

            $redirectTo = $this->_urlGenerator->action(
                InstitutionReadController::class,
                [
                    'institution' => $parentInstitution,
                ],
            );
        }

        // Redirect the user
        return $this->_redirector
            ->to($redirectTo)
            ->with('success', [__('toasts.studentGroups.created')]);
    }
}
