<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Admin\StudentGroups;

use App\ApplicationServices\StudentGroups\Create\CreateStudentGroupCommand;
use App\Core\Contracts\Cqrs\ICommandBus;
use App\Core\Enums\Permission;
use App\Core\Models\{Institution, StudentGroup};
use App\Http\Web\Controllers\Admin\Institutions\InstitutionReadController;
use App\Http\Web\Controllers\Controller;
use Codestage\Authorization\Attributes\Authorize;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Validation\ValidationException;
use ReflectionException;
use RuntimeException;
use Spatie\RouteAttributes\Attributes\Post;

final readonly class StoreStudentGroupController extends Controller
{
    public function __construct(
        private ICommandBus $_commandBus,
        private Redirector $_redirector,
        private UrlGenerator $_urlGenerator,
    ) {
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
    public function __invoke(
        StoreStudentGroupRequest $request,
    ): RedirectResponse {
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
