<?php

declare(strict_types=1);

namespace App\Infrastructure\Providers;

use App\{
    CommandBus,
    Core\Contracts\Services\IInertiaService,
    Core\Contracts\Services\IStudentGroupService,
    Core\Services\InertiaService,
    Core\Services\StudentGroupService,
    QueryBus,
};
use App\Core\Contracts\Cqrs\{ICommandBus, IQueryBus};
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\{ServiceProvider, Str};

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind application services
        $this->app->bind(IInertiaService::class, InertiaService::class);
        $this->app->bind(
            IStudentGroupService::class,
            StudentGroupService::class,
        );

        // Setup CQRS
        $this->app->singleton(IQueryBus::class, QueryBus::class);
        $this->app->singleton(ICommandBus::class, CommandBus::class);

        // Configure custom application namespaces
        Factory::guessFactoryNamesUsing(
            /**
             * @param class-string<Model> $modelName
             */
            function (string $modelName) {
                $appNamespace = $this->app->getNamespace();

                $modelName = Str::startsWith(
                    $modelName,
                    $appNamespace . 'Core\Models\\',
                )
                    ? Str::after($modelName, $appNamespace . 'Core\Models\\')
                    : Str::after($modelName, $appNamespace);

                /** @var class-string<Factory<Model>> $result */
                $result = Factory::$namespace . $modelName . 'Factory';

                return $result;
            },
        );
        Factory::guessModelNamesUsing(function (Factory $factory) {
            $namespacedFactoryBasename = Str::replaceLast(
                'Factory',
                '',
                Str::replaceFirst(Factory::$namespace, '', $factory::class),
            );

            $factoryBasename = Str::replaceLast(
                'Factory',
                '',
                class_basename($factory),
            );

            $appNamespace = $this->app->getNamespace();

            /** @var class-string<Model> $result */
            $result = class_exists(
                $appNamespace . 'Core\\Models\\' . $namespacedFactoryBasename,
            )
                ? $appNamespace . 'Core\\Models\\' . $namespacedFactoryBasename
                : $appNamespace . $factoryBasename;

            return $result;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
