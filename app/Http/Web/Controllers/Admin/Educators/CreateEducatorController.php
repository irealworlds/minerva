<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Admin\Educators;

use Inertia\{Response as InertiaResponse, ResponseFactory};
use Spatie\RouteAttributes\Attributes\Get;

final readonly class CreateEducatorController
{
    public function __construct(private ResponseFactory $_inertia)
    {
    }

    #[Get('/Admin/Educators/Create', name: 'admin.educators.create')]
    public function __invoke(): InertiaResponse
    {
        return $this->_inertia->render('Admin/Educators/Create');
    }
}
