<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers;

use Codestage\Authorization\Attributes\Authorize;
use Inertia\{Inertia, Response};
use RuntimeException;
use Spatie\RouteAttributes\Attributes\Get;

final readonly class DashboardController extends Controller
{
    /**
     * @throws RuntimeException
     */
    #[Get('/Dashboard', name: 'dashboard', middleware: 'verified')]
    #[Authorize]
    public function __invoke(): Response
    {
        return Inertia::render('Dashboard');
    }
}
