<?php

declare(strict_types=1);

namespace App\Core\Contracts\Services;

interface IInertiaService
{
    /**
     * Add a new toast to be displayed on renders made as response to the current request.
     */
    public function addToastToCurrentRequest(string $type, string $message): void;
}
