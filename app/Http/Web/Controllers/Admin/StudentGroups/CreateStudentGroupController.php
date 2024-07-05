<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Admin\StudentGroups;

use App\Core\Contracts\Services\IInertiaService;
use App\Core\Enums\Permission;
use App\Core\Models\{Institution, StudentGroup};
use App\Http\Web\ViewModels\{StudentGroupViewModel};
use App\Http\Web\ViewModels\Assemblers\InstitutionViewModelAssembler;
use Codestage\Authorization\Attributes\Authorize;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\{Request};
use Inertia\{Response as InertiaResponse, ResponseFactory};
use InvalidArgumentException;
use Spatie\RouteAttributes\Attributes\{Get};

final readonly class CreateStudentGroupController
{
    public function __construct(
        private IInertiaService $_inertiaService,
        private ResponseFactory $_inertia,
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
    public function __invoke(Request $request): InertiaResponse
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
}
