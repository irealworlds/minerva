<?php

declare(strict_types=1);

namespace App\Infrastructure\Providers;

use App\{
    CommandBus,
    QueryBus};
use App\Core\Contracts\Cqrs\{
    ICommandBus,
    IQueryBus};
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
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
