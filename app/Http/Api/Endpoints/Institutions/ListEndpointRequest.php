<?php

declare(strict_types=1);

namespace App\Http\Api\Endpoints\Institutions;

use App\Core\Traits\Requests\WithOptionals;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string|null $search
 */
final class ListEndpointRequest extends FormRequest
{
    use WithOptionals;
}
