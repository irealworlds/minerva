<?php

namespace App\Http\Rules;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rules\Exists;

/**
 * @template TModel of Model
 */
final class ExistsRouteKey extends Exists
{
    /**
     * @param class-string<TModel> $model
     */
    function __construct(string $model) {
        parent::__construct(
            (new $model)->getTable(),
            (new $model)->getRouteKeyName()
        );
    }
}
