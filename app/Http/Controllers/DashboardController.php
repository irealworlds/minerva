<?php

namespace App\Http\Controllers;

use Codestage\Authorization\Attributes\Authorize;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\RouteAttributes\Attributes\Get;

final class DashboardController extends Controller
{
    #[Get("/Dashboard", name: "dashboard", middleware: "verified")]
    #[Authorize]
    public function __invoke(): Response
    {
        return Inertia::render('Dashboard');
    }
}
