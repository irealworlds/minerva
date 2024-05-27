<?php

declare(strict_types=1);

namespace App\Cli\Commands;

use Illuminate\Foundation\Console\RequestMakeCommand as BaseRequestMakeCommand;
use Override;
use Symfony\Component\Console\Input\InputOption;

final class RequestMakeCommand extends BaseRequestMakeCommand
{
    /** @inheritDoc  */
    #[Override]
    protected function getDefaultNamespace($rootNamespace): string
    {
        if ($this->option('api')) {
            return $rootNamespace.'\Http\Api\Requests';
        } else {
            return $rootNamespace.'\Http\Web\Requests';
        }
    }

    /**
     * @inheritDoc
     *
     * @return array<int, array<int, string|int>>
     */
    #[Override]
    protected function getOptions(): array
    {
        /** @var array<int, array<int, string|int>> $parentOptions */
        $parentOptions = parent::getOptions();

        return [
            ...$parentOptions,
            ['api', '', InputOption::VALUE_NONE, 'Create the request for API endpoints.'],
        ];
    }
}
