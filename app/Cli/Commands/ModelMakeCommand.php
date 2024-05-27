<?php

declare(strict_types=1);

namespace App\Cli\Commands;

use const DIRECTORY_SEPARATOR;

final class ModelMakeCommand extends
    \Illuminate\Foundation\Console\ModelMakeCommand
{
    /** @inheritDoc */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return is_dir(app_path('Core' . DIRECTORY_SEPARATOR . 'Models'))
            ? $rootNamespace . '\\Core\\Models'
            : $rootNamespace;
    }
}
