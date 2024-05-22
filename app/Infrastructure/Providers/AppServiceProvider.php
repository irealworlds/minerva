<?php

namespace App\Infrastructure\Providers;

use App\CommandBus;
use App\Core\Contracts\Cqrs\ICommandBus;
use App\Core\Contracts\Cqrs\IQueryBus;
use App\QueryBus;
use Illuminate\Support\ServiceProvider;

class
AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(IQueryBus::class, QueryBus::class);
        $this->app->singleton(ICommandBus::class, CommandBus::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
