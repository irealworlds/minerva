<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Institutions\Management;

use App\ApplicationServices\EducatorInvitations\ListOutstandingForInstitution\ListOutstandingInvitationsForInstitutionQuery;
use App\ApplicationServices\Educators\ListFilteredPaginated\ListFilteredPaginatedEducatorsQuery;
use App\ApplicationServices\Institutions\ListFilteredPaginatedEducators\ListFilteredPaginatedInstitutionEducatorsQuery;
use App\ApplicationServices\Institutions\ListParents\ListInstitutionParentsQuery;
use App\Core\Contracts\Cqrs\IQueryBus;
use App\Core\Models\{
    Educator,
    EducatorInvitation,
    Institution,
    InstitutionEducator,
};
use App\Core\Optional;
use App\Http\Web\Controllers\Controller;
use App\Http\Web\ViewModels\{InstitutionEducatorViewModel};
use App\Http\Web\ViewModels\{
    Assemblers\InstitutionViewModelAssembler,
    EducatorSuggestionViewModel,
    OutstandingEducatorInvitationViewModel,
};
use Illuminate\Http\Request;
use Illuminate\Support\Enumerable;
use Inertia\{Inertia, Response as InertiaResponse};
use RuntimeException;
use Spatie\RouteAttributes\Attributes\Get;

final readonly class ManageInstitutionEducatorsController extends Controller
{
    public function __construct(
        private IQueryBus $_queryBus,
        private InstitutionViewModelAssembler $_institutionViewModelAssembler,
    ) {
    }

    /**
     * @throws RuntimeException
     */
    #[
        Get(
            '/Institutions/Manage/{institution}/Educators',
            name: 'institutions.show.educators',
        ),
    ]
    public function __invoke(
        Institution $institution,
        Request $request,
    ): InertiaResponse {
        // Parse the search query filter
        $searchFilter = Optional::empty();
        if ($request->filled('search')) {
            $searchFilter = Optional::of(
                $request->string('search')->toString(),
            );
        }

        // Run a query to get the educators
        $educators = $this->_queryBus->dispatch(
            new ListFilteredPaginatedInstitutionEducatorsQuery(
                institutionId: $institution->getKey(),
                pageSize: $request->integer('pageSize', 10),
                page: $request->integer('page', 1),
                searchQuery: $searchFilter,
            ),
        );

        // Map the educators to view models
        $educators->setCollection(
            $educators
                ->getCollection()
                ->map(
                    fn (
                        InstitutionEducator $educator,
                    ) => InstitutionEducatorViewModel::fromModel($educator),
                ),
        );

        // Render the management view
        return Inertia::render('Institutions/ManageInstitutionEducators', [
            'institution' => fn () => $this->_institutionViewModelAssembler->assemble(
                $institution,
            ),
            'educators' => static fn () => $educators,
            'outstandingInvitations' => fn () => $this->_queryBus
                ->dispatch(
                    new ListOutstandingInvitationsForInstitutionQuery(
                        institution: $institution,
                    ),
                )
                ->map(
                    static fn (
                        EducatorInvitation $invitation,
                    ) => OutstandingEducatorInvitationViewModel::fromModel(
                        $invitation,
                    ),
                ),
            'suggestions' => fn () => $this->getEducatorSuggestions(
                $institution,
            ),
        ]);
    }

    /**
     * @return Enumerable<int, EducatorSuggestionViewModel>
     */
    private function getEducatorSuggestions(
        Institution $institution,
    ): Enumerable {
        $parentInstitutions = $this->_queryBus->dispatch(
            new ListInstitutionParentsQuery(leaf: $institution),
        );

        return $this->_queryBus
            ->dispatch(
                new ListFilteredPaginatedEducatorsQuery(
                    pageSize: 10,
                    page: 1,
                    searchQuery: Optional::empty(),
                    associatedToInstitutionIds: Optional::of(
                        $parentInstitutions->map(
                            static fn (Institution $parent) => $parent->getKey(),
                        ),
                    ),
                    notAssociatedToInstitutionIds: Optional::of([
                        $institution->getKey(),
                    ]),
                ),
            )
            ->getCollection()
            ->values()
            ->map(
                static fn (
                    Educator $e,
                ) => EducatorSuggestionViewModel::fromModel($e),
            );
    }
}
