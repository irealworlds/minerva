<?php

declare(strict_types=1);

namespace App\Infrastructure\Providers;

use App\{
    CommandBus,
    Core\Contracts\Services\IEducatorInvitationService,
    Core\Contracts\Services\IInertiaService,
    Core\Contracts\Services\ISignedUrlGenerator,
    Core\Contracts\Services\IStudentGroupService,
    Core\Services\EducatorInvitationService,
    Core\Services\InertiaService,
    Core\Services\SignedUrlGenerator,
    Core\Services\StudentGroupService,
    Http\RouteValidators\CaseInsensitiveUriValidator,
    QueryBus,
};
use App\Core\Contracts\Cqrs\{ICommandBus, IQueryBus};
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Matching\UriValidator;
use Illuminate\Routing\Route;
use Illuminate\Support\{ServiceProvider, Str};

use function is_object;

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
            IEducatorInvitationService::class,
            EducatorInvitationService::class,
        );
        $this->app->bind(
            IStudentGroupService::class,
            StudentGroupService::class,
        );
        $this->app->bind(ISignedUrlGenerator::class, SignedUrlGenerator::class);

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

        // Replace the default URL Validator with the case-insensitive one
        $validators = Route::getValidators();
        $validators[] = new CaseInsensitiveUriValidator();
        Route::$validators = array_filter(
            $validators,
            fn ($validator) => is_object($validator) &&
                $validator::class !== UriValidator::class,
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
