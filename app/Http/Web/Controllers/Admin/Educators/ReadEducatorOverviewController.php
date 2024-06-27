<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Admin\Educators;

use App\Core\Models\Educator;
use App\Http\Web\ViewModels\Assemblers\Admin\EducatorOverviewViewModelAssembler;
use Codestage\Authorization\Attributes\Authorize;
use Inertia\{Response as InertiaResponse, ResponseFactory};
use Spatie\RouteAttributes\Attributes\Get;

final readonly class ReadEducatorOverviewController
{
    public function __construct(
        private ResponseFactory $_inertia,
        private EducatorOverviewViewModelAssembler $_educatorOverviewViewModelAssembler,
    ) {
    }

    #[
        Get(
            '/Admin/Educators/Details/{educator}/Overview',
            name: 'admin.educators.read.overview',
        ),
    ]
    #[Authorize]
    public function __invoke(Educator $educator): InertiaResponse
    {
        return $this->_inertia->render('Admin/Educators/ReadOverview', [
            'educator' => $this->_educatorOverviewViewModelAssembler->assemble(
                $educator,
            ),
        ]);
    }
}
