<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\StudentEnrolments;

use App\ApplicationServices\Institutions\FindById\FindInstitutionsByRouteKeysQuery;
use App\Core\Contracts\Cqrs\IQueryBus;
use App\Core\Contracts\Services\IInertiaService;
use App\Http\Web\Controllers\Controller;
use App\Http\Web\ViewModels\Assemblers\InstitutionViewModelAssembler;
use Illuminate\Http\Request;
use Inertia\{Response as InertiaResponse, ResponseFactory};
use Spatie\RouteAttributes\Attributes\Get;
use Throwable;

use function is_string;

final readonly class CreateStudentEnrolmentController extends Controller
{
    public function __construct(
        private ResponseFactory $_inertia,
        private IQueryBus $_queryBus,
        private IInertiaService $_inertiaService,
        private InstitutionViewModelAssembler $_institutionViewModelAssembler,
    ) {
    }

    #[Get('/StudentEnrolments/Create', name: 'student_enrolments.create')]
    public function __invoke(Request $request): InertiaResponse
    {
        // Get the intended institution from the query string
        $institutionKey = $request->query('institutionKey');
        $intendedInstitution = null;
        if (!empty($institutionKey) && is_string($institutionKey)) {
            try {
                $intendedInstitution = $this->_queryBus
                    ->dispatch(
                        new FindInstitutionsByRouteKeysQuery($institutionKey),
                    )
                    ->firstOrFail();
            } catch (Throwable) {
                $message = __(
                    'toasts.studentEnrolments.cannot_suggest_institution',
                );
                if (!is_string($message)) {
                    $message = 'Cannot suggest institution';
                }
                $this->_inertiaService->addToastToCurrentRequest(
                    'warning',
                    $message,
                );
            }
        }

        // Render the inertia page
        return $this->_inertia->render('StudentEnrolments/Create', [
            'intendedInstitution' => $intendedInstitution
                ? $this->_institutionViewModelAssembler->assemble(
                    $intendedInstitution,
                )
                : null,
        ]);
    }
}
