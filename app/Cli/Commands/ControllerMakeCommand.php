<?php

declare(strict_types=1);

namespace App\Cli\Commands;

use Illuminate\Routing\Console\ControllerMakeCommand as BaseControllerMakeCommand;
use Override;

final class ControllerMakeCommand extends BaseControllerMakeCommand
{

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     */
    #[Override]
    protected function getDefaultNamespace($rootNamespace): string
    {
        if ($this->option('api')) {
            return $rootNamespace.'\Http\Api\Endpoints';
        } else {
            return $rootNamespace.'\Http\Web\Controllers';
        }
    }
}
