<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Admin\StudentGroups;

use App\ApplicationServices\StudentGroups\Delete\DeleteStudentGroupCommand;
use App\Core\Contracts\Cqrs\ICommandBus;
use App\Core\Enums\Permission;
use App\Core\Exceptions\InvalidOperationException;
use App\Core\Models\{Identity, StudentGroup};
use App\Http\Web\Controllers\{Controller};
use App\Http\Web\Controllers\DashboardController;
use Codestage\Authorization\Attributes\Authorize;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Factory;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use ReflectionException;
use Spatie\RouteAttributes\Attributes\Delete;

final readonly class DeleteStudentGroupController extends Controller
{
    public function __construct(
        private ICommandBus $_commandBus,
        private Factory $_authManager,
        private Redirector $_redirector,
        private UrlGenerator $_urlGenerator,
    ) {
    }

    /**
     * @throws AuthenticationException
     * @throws BindingResolutionException
     * @throws ReflectionException
     */
    #[
        Delete(
            '/Admin/StudentGroups/{group}',
            name: 'admin.student_groups.delete',
        ),
    ]
    #[Authorize(permissions: Permission::StudentGroupDelete)]
    public function __invoke(StudentGroup $group): RedirectResponse
    {
        $identity = $this->_authManager->guard()->user();

        if (!($identity instanceof Identity)) {
            throw new AuthenticationException();
        }

        try {
            /** @throws InvalidOperationException
             * @throws ReflectionException
             * @throws BindingResolutionException */
            $this->_commandBus->dispatch(
                new DeleteStudentGroupCommand($group, $identity),
            );
        } catch (InvalidOperationException $e) {
            return $this->_redirector
                ->back()
                ->with('error', [__('toasts.studentGroups.cannot_delete')]);
        }

        return $this->_redirector
            ->intended(
                default: $this->_urlGenerator->previous(
                    fallback: $this->_urlGenerator->action(
                        DashboardController::class,
                    ),
                ),
            )
            ->with('success', [__('toasts.studentGroups.deleted')]);
    }
}
