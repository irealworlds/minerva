<?php

declare(strict_types=1);

namespace App\Http\Web\ViewModels\Assemblers;

use App\ApplicationServices\StudentGroupEnrolments\ListFilteredPaginatedByInstitution\ListFilteredPaginatedStudentGroupEnrolmentsByInstitutionQuery;
use App\Core\Contracts\Cqrs\IQueryBus;
use App\Core\Models\Institution;
use App\Core\Optional;
use App\Http\Web\ViewModels\InstitutionViewModel;
use RuntimeException;

final readonly class InstitutionViewModelAssembler
{
    public function __construct(private IQueryBus $_queryBus)
    {
    }

    /**
     * Assemble a new {@link InstitutionViewModel} based on the given {@link $institution institution}.
     */
    public function assemble(Institution $model): InstitutionViewModel
    {
        $pictureUri = $model->getFirstMediaUrl(
            Institution::EmblemPictureMediaCollection,
        );
        if (empty($pictureUri)) {
            $pictureUri = null;
        }

        $studentEnrolments = $this->_queryBus->dispatch(
            new ListFilteredPaginatedStudentGroupEnrolmentsByInstitutionQuery(
                institution: $model,
                page: 1,
                pageSize: 1,
                searchQuery: Optional::empty(),
            ),
        );

        return new InstitutionViewModel(
            id: $model->getRouteKey(),
            name: $model->name,
            website: $model->website,
            pictureUri: $pictureUri,
            ancestors: $this->getAncestors($model),
            educatorsCount: $model->educators()->count(),
            studentsCount: $studentEnrolments->total(),
            disciplinesCount: $model->disciplines()->count(),
            childInstitutions: $model->children->map(static function (
                mixed $child,
            ) {
                if (!($child instanceof Institution)) {
                    throw new RuntimeException(
                        'A child institution is not an instance of ' .
                            Institution::class,
                    );
                }

                return (object) [
                    'id' => $child->getRouteKey(),
                    'name' => $child->name,
                ];
            }),
            createdAt: $model->created_at->toIso8601String(),
            updatedAt: $model->updated_at->toIso8601String(),
        );
    }

    /**
     * @return iterable<object{id: mixed, name: string}>
     */
    protected function getAncestors(Institution $model): iterable
    {
        if ($model->parent === null) {
            return [];
        }

        return [
            ...$this->getAncestors($model->parent),
            (object) [
                'id' => $model->parent->getRouteKey(),
                'name' => $model->parent->name,
            ],
        ];
    }
}
