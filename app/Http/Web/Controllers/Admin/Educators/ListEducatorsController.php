<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Admin\Educators;

use App\ApplicationServices\Educators\ListFilteredPaginated\ListFilteredPaginatedEducatorsQuery;
use App\Core\Contracts\Cqrs\IQueryBus;
use App\Core\Models\Educator;
use App\Core\Optional;
use App\Http\Web\ViewModels\Admin\EducatorViewModel;
use App\Http\Web\ViewModels\Assemblers\Admin\EducatorViewModelAssembler;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\AbstractPaginator;
use Inertia\{Response as InertiaResponse, ResponseFactory};
use Spatie\RouteAttributes\Attributes\Get;

final readonly class ListEducatorsController
{
    public function __construct(
        private IQueryBus $_queryBus,
        private ResponseFactory $_inertia,
        private EducatorViewModelAssembler $_educatorViewModelAssembler,
    ) {
    }

    #[Get('/Admin/Educators/List', name: 'admin.educators.list')]
    public function __invoke(ListEducatorsRequest $request): InertiaResponse
    {
        return $this->_inertia->render('Admin/Educators/List', [
            'educators' => fn () => $this->getEducatorsList(
                page: $request->integer('page'),
                pageSize: $request->integer('pageSize'),
                searchQuery: $request->optionalString('searchQuery', false),
            ),
        ]);
    }

    /**
     * @param Optional<string> $searchQuery
     * @return LengthAwarePaginator<EducatorViewModel>&AbstractPaginator<EducatorViewModel>
     */
    protected function getEducatorsList(
        int $page,
        int $pageSize,
        Optional $searchQuery,
    ): AbstractPaginator&LengthAwarePaginator {
        $educators = $this->_queryBus->dispatch(
            new ListFilteredPaginatedEducatorsQuery(
                pageSize: $pageSize,
                page: $page,
                searchQuery: $searchQuery,
            ),
        );

        /** @var LengthAwarePaginator<EducatorViewModel>&AbstractPaginator<EducatorViewModel> $mappedEducators */
        $mappedEducators = $educators->setCollection(
            $educators
                ->getCollection()
                ->map(
                    fn (
                        Educator $educator,
                    ) => $this->_educatorViewModelAssembler->assemble(
                        $educator,
                    ),
                ),
        );

        $mappedEducators->withQueryString();

        return $mappedEducators;
    }
}
