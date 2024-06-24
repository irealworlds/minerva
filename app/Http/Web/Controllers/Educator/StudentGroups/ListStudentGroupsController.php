<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Educator\StudentGroups;

use App\ApplicationServices\StudentGroups\ListByEducatorFilteredPaginated\ListStudentGroupsByEducatorFilteredPaginatedQuery;
use App\Core\Contracts\Cqrs\IQueryBus;
use App\Core\Models\Educator;
use App\Core\Models\Identity;
use App\Core\Models\StudentGroup;
use App\Core\Models\StudentGroupDisciplineEducator;
use App\Http\Web\Controllers\Controller;
use App\Http\Web\ViewModels\Assemblers\EducatorStudentGroupViewModelAssembler;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Auth\Factory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;
use Inertia\Response as InertiaResponse;
use Inertia\ResponseFactory;
use Spatie\RouteAttributes\Attributes\Get;

final readonly class ListStudentGroupsController extends Controller
{
    function __construct(
        private IQueryBus $_queryBus,
        private ResponseFactory $_inertia,
        private EducatorStudentGroupViewModelAssembler $_viewModelAssembler,
        private Factory $_authManager,
    ) {
    }

    #[Get('/Educators/StudentGroups', name: 'educators.studentGroups.list')]
    public function __invoke(ListStudentGroupsRequest $request): InertiaResponse
    {
        return $this->_inertia->render('Educator/StudentGroups/List', [
            'studentGroups' => fn() => $this->getStudentGroups($request),
            'initialFilters' => [
                'searchQuery' => $request->string('searchQuery', ''),
            ],
        ]);
    }

    protected function getCurrentEducatorProfile(): Educator|null
    {
        /** @var Identity $identity */
        $identity = $this->_authManager->guard()->user();
        return $identity->educatorProfile;
    }

    /**
     * @return LengthAwarePaginator|mixed
     * @throws AuthorizationException
     * @throws ValidationException
     */
    protected function getStudentGroups(
        ListStudentGroupsRequest $request,
    ): mixed {
        // Get the current educator profile
        $educator = $this->getCurrentEducatorProfile();

        if (empty($educator)) {
            throw new AuthorizationException();
        }

        // Fetch the student groups
        $studentGroups = $this->_queryBus->dispatch(
            new ListStudentGroupsByEducatorFilteredPaginatedQuery(
                page: $request->integer('page'),
                pageSize: $request->integer('pageSize', 6),
                educatorKey: $educator->getKey(),
                searchQuery: $request->optionalString('searchQuery', false),
            ),
        );

        $associations = $educator
            ->hasMany(StudentGroupDisciplineEducator::class)
            ->get();

        // Assemble the view models
        $studentGroups->setCollection(
            $studentGroups
                ->getCollection()
                ->map(
                    fn(
                        StudentGroup $studentGroup,
                    ) => $this->_viewModelAssembler->assemble(
                        studentGroup: $studentGroup,
                        disciplineAssociations: $associations->filter(
                            static fn(
                                StudentGroupDisciplineEducator $association,
                            ) => $association->student_group_id ===
                                $studentGroup->getKey(),
                        ),
                    ),
                ),
        );

        // Return the student group view models
        return $studentGroups;
    }
}
