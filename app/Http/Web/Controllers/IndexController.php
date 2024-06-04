<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers;

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\{Inertia, Response};
use RuntimeException;
use Spatie\RouteAttributes\Attributes\Get;
use const PHP_VERSION;

readonly class IndexController extends Controller
{
    /**
     * @throws RuntimeException
     */
    #[Get('/')]
    public function __invoke(Request $request): Response
    {
        return Inertia::render('Welcome', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
        ]);
    }
}
