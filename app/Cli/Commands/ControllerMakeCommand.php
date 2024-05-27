<?php

declare(strict_types=1);

namespace App\Cli\Commands;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Console\ControllerMakeCommand as BaseControllerMakeCommand;
use Illuminate\Support\Str;
use Override;

use const DIRECTORY_SEPARATOR;

final class ControllerMakeCommand extends BaseControllerMakeCommand
{
    /** @inheritDoc  */
    #[Override]
    protected function getDefaultNamespace($rootNamespace): string
    {
        if ($this->option('api')) {
            return $rootNamespace . '\Http\Api\Endpoints';
        } else {
            return $rootNamespace . '\Http\Web\Controllers';
        }
    }

    /**     *
     * @param class-string<Model> $modelClass
     * @param string $storeRequestClass
     * @param string $updateRequestClass
     *
     * @return array{0: string, 1: string}
     */
    #[Override]
    protected function generateFormRequests(
        $modelClass,
        $storeRequestClass,
        $updateRequestClass,
    ): array {
        $creationOptions = [];
        if ($this->option('api')) {
            $creationOptions['--api'] = true;
        }

        $storeRequestClass = class_basename($modelClass) . 'StoreRequest';

        $this->call(
            RequestMakeCommand::class,
            array_merge(
                [
                    'name' => $storeRequestClass,
                ],
                $creationOptions,
            ),
        );

        $updateRequestClass = class_basename($modelClass) . 'UpdateRequest';

        $this->call(
            RequestMakeCommand::class,
            array_merge(
                [
                    'name' => $updateRequestClass,
                ],
                $creationOptions,
            ),
        );

        return [$storeRequestClass, $updateRequestClass];
    }

    /**
     * @param array<string, string> $replace
     * @param class-string<Model> $modelClass
     *
     * @return array<string, string>
     */
    #[Override]
    protected function buildFormRequestReplacements(
        array $replace,
        $modelClass,
    ): array {
        /** @var array<string, string> $baseReplacements */
        $baseReplacements = parent::buildFormRequestReplacements(
            $replace,
            $modelClass,
        );

        return array_map(function (string $replacedValue) {
            if (Str::startsWith($replacedValue, 'App\\Http\\Requests')) {
                $replacedValue = Str::replace(
                    'App\\Http\\Requests',
                    $this->option('api')
                        ? 'App\\Http\\Api\\Requests'
                        : 'App\\Http\\Web\\Requests',
                    $replacedValue,
                );
            }
            return $replacedValue;
        }, $baseReplacements);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    protected function qualifyModel(string $model): string
    {
        $model = ltrim($model, '\\/');

        $model = str_replace('/', '\\', $model);

        $rootNamespace = $this->rootNamespace();

        if (Str::startsWith($model, $rootNamespace)) {
            return $model;
        }

        return is_dir(app_path('Core' . DIRECTORY_SEPARATOR . 'Models'))
            ? $rootNamespace . 'Core\Models\\' . $model
            : $rootNamespace . $model;
    }
}
