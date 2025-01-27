<?php

declare(strict_types=1);

use App\Cli\Commands\{
    ControllerMakeCommand,
    ModelMakeCommand,
    RequestMakeCommand,
};
use App\Http\Web\Middleware\HandleInertiaRequestsMiddleware;
use Codestage\Authorization\Middleware\AuthorizationMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\{Exceptions, Middleware};
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;

return Application::configure(basePath: \dirname(__DIR__))
    ->withCommands([
        // Override default make commands to work with the custom application namespaces
        ControllerMakeCommand::class,
        RequestMakeCommand::class,
        ModelMakeCommand::class,
    ])
    ->withRouting(health: '/up')
    ->withMiddleware(static function (Middleware $middleware): void {
        $middleware->web(
            append: [
                HandleInertiaRequestsMiddleware::class,
                AddLinkHeadersForPreloadedAssets::class,
            ],
        );

        $middleware->statefulApi();

        //
        $middleware->appendToGroup('web', AuthorizationMiddleware::class);
        $middleware->appendToGroup('api', AuthorizationMiddleware::class);
    })
    ->withExceptions(static function (Exceptions $exceptions): void {
        //
    })
    ->create();
