<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Disciplines;

use App\ApplicationServices\Disciplines\Create\DisciplineCreateCommand;
use App\ApplicationServices\Institutions\FindById\FindInstitutionsByRouteKeysQuery;
use App\Core\Contracts\Cqrs\{ICommandBus, IQueryBus};
use App\Core\Contracts\Services\IInertiaService;
use App\Core\Models\Institution;
use App\Http\Web\Controllers\{Controller, DashboardController};
use App\Http\Web\Controllers\Institutions\Management\ManageInstitutionDisciplinesController;
use App\Http\Web\Requests\Disciplines\DisciplineCreateRequest;
use App\Http\Web\ViewModels\InstitutionViewModel;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Http\{RedirectResponse, Request};
use Illuminate\Routing\Redirector;
use Illuminate\Support\ItemNotFoundException;
use Inertia\{Response as InertiaResponse, ResponseFactory};
use ReflectionException;
use Spatie\RouteAttributes\Attributes\{Get, Post};

use function is_string;

final readonly class CreateDisciplineController extends Controller
{
    public function __construct(
        private ResponseFactory $_inertiaResponse,
        private IInertiaService $_inertiaService,
        private Redirector $_redirector,
        private UrlGenerator $_urlGenerator,
        private IQueryBus $_queryBus,
        private ICommandBus $_commandBus,
    ) {
    }

    #[Get('/Disciplines/Create', name: 'disciplines.create')]
    public function create(Request $request): InertiaResponse
    {
        // Get intended institutions from the query params
        $institutionSuggestions = null;
        if ($request->query->has('institutions')) {
            $institutionKeys = array_filter(
                explode(',', $request->query->getString('institutions')),
                fn (mixed $key) => !empty($key),
            );
            $institutionSuggestions = $this->_queryBus->dispatch(
                new FindInstitutionsByRouteKeysQuery(...$institutionKeys),
            );

            if ($institutionSuggestions->isEmpty()) {
                $message = __('toasts.disciplines.cannot_suggest_institution');
                if (is_string($message)) {
                    $this->_inertiaService->addToastToCurrentRequest(
                        'warning',
                        $message,
                    );
                }
            }
        }

        // Render the inertia page
        return $this->_inertiaResponse->render('Disciplines/Create', [
            'initialInstitutions' => fn () => $institutionSuggestions?->map(
                static fn (
                    Institution $institution,
                ) => InstitutionViewModel::fromModel($institution),
            ) ?? [],
        ]);
    }

    /**
     * @throws ReflectionException
     * @throws BindingResolutionException
     */
    #[Post('/Disciplines', 'disciplines.store')]
    public function store(DisciplineCreateRequest $request): RedirectResponse
    {
        // Reify associated institutions
        $associatedInstitutions = null;
        if ($request->filled('associatedInstitutionKeys')) {
            $associatedInstitutions = $this->_queryBus->dispatch(
                new FindInstitutionsByRouteKeysQuery(
                    ...$request->associatedInstitutionKeys,
                ),
            );
        }

        // Dispatch the command to create the discipline
        $this->_commandBus->dispatch(
            new DisciplineCreateCommand(
                name: $request->name,
                abbreviation: $request->abbreviation,
                associatedInstitutions: $associatedInstitutions ?? [],
            ),
        );

        // Guess the location where the user should be redirected to
        try {
            $redirectTo = match (true) {
                $associatedInstitutions?->isNotEmpty()
                    => $this->_urlGenerator->action(
                        ManageInstitutionDisciplinesController::class,
                        [
                        'institution' => $associatedInstitutions
                            ->firstOrFail()
                            ->getKey(),
                    ],
                    ),

                default => $this->_urlGenerator->previous(
                    fallback: $this->_urlGenerator->action(
                        DashboardController::class,
                    ),
                ),
            };
        } catch (ItemNotFoundException) {
            $redirectTo = $this->_urlGenerator->action(
                DashboardController::class,
            );
        }

        // Redirect the user to the appropriate location
        return $this->_redirector
            ->to($redirectTo)
            ->with('success', [__('toasts.disciplines.created')]);
    }
}
