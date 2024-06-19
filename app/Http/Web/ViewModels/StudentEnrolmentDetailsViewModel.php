<?php

declare(strict_types=1);

namespace App\Http\Web\ViewModels;

use App\Core\Models\{Institution, StudentGroup, StudentGroupEnrolment};
use RuntimeException;

final readonly class StudentEnrolmentDetailsViewModel
{
    /**
     * @param iterable<array{id: mixed, name: string}> $allEnrolmentsList
     * @param iterable<array{id: mixed, name: string}> $studentGroupAncestors
     * @param iterable<array{id: mixed, name: string}> $parentInstitutionAncestors
     */
    public function __construct(
        public mixed $id,
        public mixed $studentRegistrationId,
        public string $studentName,
        public mixed $studentGroupKey,
        public string $studentGroupName,
        public iterable $studentGroupAncestors,
        public int $enroledDisciplineCount,
        public int $studentGroupDisciplineCount,
        public iterable $allEnrolmentsList,
        public mixed $parentInstitutionId,
        public string $parentInstitutionName,
        public iterable $parentInstitutionAncestors,
        public string $enroledAt,
    ) {
    }

    /**
     * @throws RuntimeException
     */
    public static function fromModel(
        StudentGroupEnrolment $model,
    ): StudentEnrolmentDetailsViewModel {
        $parentInstitution = $model->studentGroup
            ->ancestors()
            ->where('parent_type', Institution::class)
            ->first()?->parent;

        if (!($parentInstitution instanceof Institution)) {
            throw new RuntimeException('Parent institution not found');
        }

        return new StudentEnrolmentDetailsViewModel(
            id: $model->getRouteKey(),
            studentRegistrationId: $model->studentRegistration->getRouteKey(),
            studentName: $model->studentRegistration->identity->name->getFullName(),
            studentGroupKey: $model->studentGroup->getRouteKey(),
            studentGroupName: $model->studentGroup->name,
            studentGroupAncestors: $model->studentGroup
                ->ancestors()
                ->get()
                ->map(
                    static fn (StudentGroup $ancestor) => [
                        'id' => $ancestor->getRouteKey(),
                        'name' => $ancestor->name,
                    ],
                ),
            enroledDisciplineCount: 1245,
            studentGroupDisciplineCount: $model->studentGroup
                ->disciplines()
                ->count(),
            allEnrolmentsList: $model->studentRegistration
                ->studentGroupEnrolments()
                ->with(
                    (new StudentGroupEnrolment())
                        ->studentGroup()
                        ->getRelationName(),
                )
                ->get()
                ->map(
                    static fn (StudentGroupEnrolment $en) => [
                        'id' => $en->getRouteKey(),
                        'name' => $en->studentGroup->name,
                    ],
                ),
            parentInstitutionId: $parentInstitution->getRouteKey(),
            parentInstitutionName: $parentInstitution->name,
            parentInstitutionAncestors: $parentInstitution
                ->ancestors()
                ->get()
                ->map(
                    static fn (Institution $ancestor) => [
                        'id' => $ancestor->getRouteKey(),
                        'name' => $ancestor->name,
                    ],
                ),
            enroledAt: $model->created_at->toIso8601String(),
        );
    }
}
