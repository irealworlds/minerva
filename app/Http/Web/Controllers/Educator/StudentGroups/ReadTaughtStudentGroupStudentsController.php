<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Educator\StudentGroups;

use App\ApplicationServices\StudentDisciplineGrades\List\ListStudentDisciplineGradesQuery;
use App\ApplicationServices\StudentGroupDisciplineEducators\List\ListStudentGroupDisciplineEducatorsQuery;
use App\ApplicationServices\StudentRegistrations\ListTaughtByEducatorFilteredPaginated\ListStudentsTaughtByEducatorFilteredPaginatedQuery;
use App\Core\Contracts\Cqrs\IQueryBus;
use App\Core\Models\Educator;
use App\Core\Models\Identity;
use App\Core\Models\StudentDisciplineGrade;
use App\Core\Models\StudentGroup;
use App\Core\Models\StudentGroupDisciplineEducator;
use App\Core\Models\StudentRegistration;
use App\Core\Optional;
use App\Http\Api\Dtos\Admin\DisciplineDto;
use App\Http\Web\Controllers\Controller;
use App\Http\Web\ViewModels\Assemblers\EducatorTaughtStudentViewModelAssembler;
use App\Http\Web\ViewModels\EducatorTaughtStudentViewModel;
use Codestage\Authorization\Attributes\Authorize;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Auth\Factory as AuthManager;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\AbstractPaginator;
use Inertia\Response as InertiaResponse;
use Inertia\ResponseFactory;
use Spatie\RouteAttributes\Attributes\Get;

final readonly class ReadTaughtStudentGroupStudentsController extends Controller
{
    function __construct(
        private ResponseFactory $_inertia,
        private IQueryBus $_queryBus,
        private AuthManager $_authManager,
        private EducatorTaughtStudentViewModelAssembler $_viewModelAssembler,
    ) {
    }

    /**
     * @throws AuthorizationException
     */
    #[
        Get(
            '/Educator/StudentGroups/{studentGroup}/Students',
            name: 'educator.studentGroups.read.students',
        ),
    ]
    #[Authorize]
    public function __invoke(
        ReadTaughtStudentGroupStudentsRequest $request,
        StudentGroup $studentGroup,
    ): InertiaResponse {
        $educator = $this->getCurrentEducatorProfile();

        if (empty($educator)) {
            throw new AuthorizationException();
        }

        return $this->_inertia->render('Educator/StudentGroups/ReadStudents', [
            'studentGroup' => [
                'id' => $studentGroup->getKey(),
                'name' => $studentGroup->name,
            ],
            'disciplines' => fn() => $this->getDisciplinesForEducator(
                $educator,
                $studentGroup,
            ),
            'students' => fn() => $this->getStudents(
                page: $request->integer('page', 1),
                pageSize: $request->integer('pageSize', 12),
                educatorKey: $educator->getKey(),
                studentGroupKey: $studentGroup->getKey(),
                disciplineKey: $request->optionalString('disciplineKey', false), // todo do not use the route keys directly
                searchQuery: $request->optionalString('searchQuery', false), // todo do not use the route keys directly
            ),
            'initialDisciplineKey' => $request->string('disciplineKey'),
        ]);
    }

    protected function getCurrentEducatorProfile(): Educator|null
    {
        /** @var Identity $identity */
        $identity = $this->_authManager->guard()->user();
        return $identity->educatorProfile;
    }

    /**
     * @return iterable<DisciplineDto>
     */
    protected function getDisciplinesForEducator(
        Educator $educator,
        StudentGroup $studentGroup,
    ): iterable {
        $educatorDisciplineAssociations = $this->_queryBus->dispatch(
            new ListStudentGroupDisciplineEducatorsQuery(
                educatorKey: $educator->getKey(),
                studentGroupKey: $studentGroup->getKey(),
            ),
        );

        return $educatorDisciplineAssociations->map(
            static fn(
                StudentGroupDisciplineEducator $disciplineEducator,
            ) => DisciplineDto::fromModel($disciplineEducator->discipline),
        );
    }

    /**
     * @param Optional<mixed> $disciplineKey
     * @param Optional<string> $searchQuery
     * @return LengthAwarePaginator<EducatorTaughtStudentViewModel>&AbstractPaginator<EducatorTaughtStudentViewModel>
     */
    protected function getStudents(
        int $page,
        int $pageSize,
        mixed $educatorKey,
        mixed $studentGroupKey,
        Optional $disciplineKey,
        Optional $searchQuery,
    ): LengthAwarePaginator&AbstractPaginator {
        $students = $this->_queryBus->dispatch(
            new ListStudentsTaughtByEducatorFilteredPaginatedQuery(
                page: $page,
                pageSize: $pageSize,
                educatorKey: $educatorKey,
                studentGroupKey: Optional::of($studentGroupKey),
                disciplineKey: $disciplineKey,
                searchQuery: $searchQuery,
            ),
        );

        // Get the student's grades
        $grades = $this->_queryBus->dispatch(
            new ListStudentDisciplineGradesQuery(
                studentRegistrationKeys: Optional::of(
                    $students
                        ->getCollection()
                        ->map(
                            static fn(
                                StudentRegistration $student,
                            ) => $student->getKey(),
                        ),
                ),
                disciplineKeys: $disciplineKey->hasValue()
                    ? Optional::of([$disciplineKey->value])
                    : Optional::empty(),
            ),
        );

        /** @var LengthAwarePaginator<EducatorTaughtStudentViewModel>&AbstractPaginator<EducatorTaughtStudentViewModel> $mappedCollection */
        $mappedCollection = $students->setCollection(
            $students
                ->getCollection()
                ->map(
                    fn(
                        StudentRegistration $student,
                    ) => $this->_viewModelAssembler->assemble(
                        student: $student,
                        grades: $grades->filter(
                            static fn(
                                StudentDisciplineGrade $grade,
                            ) => $grade->student_id === $student->getKey(),
                        ),
                    ),
                ),
        );

        return $mappedCollection;
    }
}
