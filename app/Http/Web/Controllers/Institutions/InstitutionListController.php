<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Institutions;

use App\ApplicationServices\Institutions\ListFilteredPaginated\ListFilteredPaginatedInstitutionsQuery;
use App\Core\Contracts\Cqrs\IQueryBus;
use App\Core\Enums\Permission;
use App\Core\Optional;
use App\Http\Web\Controllers\Controller;
use App\Http\Web\Requests\Institutions\InstitutionListRequest;
use App\Http\Web\ViewModels\InstitutionViewModel;
use Codestage\Authorization\Attributes\Authorize;
use Illuminate\Validation\ValidationException;
use Inertia\{
    Inertia,
    Response};
use RuntimeException;
use Spatie\RouteAttributes\Attributes\Get;

final readonly class InstitutionListController extends Controller
{
    public function __construct(private IQueryBus $_queryBus)
    {
    }

    /**
     * @throws RuntimeException
     * @throws ValidationException
     */
    #[Get('/Institutions', name: 'institutions.index')]
    #[Authorize(permissions: Permission::InstitutionsCreate)]
    public function __invoke(InstitutionListRequest $request): Response
    {
        // Fetch the institutions via a query
        $institutions = $this->_queryBus->dispatch(
            new ListFilteredPaginatedInstitutionsQuery(
                page: $request->integer('page', 1),
                pageSize: $request->integer('pageSize', 10),
                parentId: Optional::of(null),
                searchQuery: $request->optionalString('search', false),
            ),
        );

        // Map results to view models
        $institutions->setCollection(
            $institutions
                ->getCollection()
                ->map(
                    static fn (
                        mixed $institution,
                    ) => InstitutionViewModel::fromModel($institution),
                ),
        );

        $institutions = $institutions->withQueryString();

        // Render the view
        return Inertia::render('Institutions/List', [
            'institutions' => $institutions,
            'filters' => [
                'search' => $request->search,
            ],
        ]);
    }
}
