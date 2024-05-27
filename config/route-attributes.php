<?php

declare(strict_types=1);

use Illuminate\Routing\Middleware\SubstituteBindings;

return [
    /*
     *  Automatic registration of routes will only happen if this setting is `true`
     */
    'enabled' => true,

    /*
     * Controllers in these directories that have routing attributes
     * will automatically be registered.
     *
     * Optionally, you can specify group configuration by using key/values
     */
    'directories' => [
        app_path('Http/Web/Controllers') => [
            'middleware' => 'web',
            // only register routes in files that match the patterns
            'patterns' => ['*Controller.php'],
        ],
        app_path('Http/Api/Endpoints') => [
            'middleware' => 'api',
            'prefix' => 'api',
            'name' => 'api.',
            // only register routes in files that match the patterns
            'patterns' => ['*Endpoint.php'],
        ],
        /*
        app_path('Http/Controllers/Api') => [
           'prefix' => 'api',
           'middleware' => 'api',
            // only register routes in files that match the patterns
           'patterns' => ['*Controller.php'],
           // do not register routes in files that match the patterns
           'not_patterns' => [],
        ],
        */
    ],

    /**
     * This middleware will be applied to all routes.
     */
    'middleware' => [SubstituteBindings::class],
];
