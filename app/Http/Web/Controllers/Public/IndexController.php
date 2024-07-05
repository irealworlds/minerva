<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Public;

use App\Http\Web\Controllers\Auth\AuthSessions\CreateAuthSessionController;
use App\Http\Web\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Spatie\RouteAttributes\Attributes\Get;

readonly class IndexController extends Controller
{
    public function __construct(private Redirector $_redirector)
    {
    }

    #[Get('/')]
    public function __invoke(): RedirectResponse
    {
        return $this->_redirector->action(CreateAuthSessionController::class);
    }
}
