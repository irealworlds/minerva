<?php

declare(strict_types=1);

namespace App\Http\Api\Endpoints\Admin\StudentRegistrations;

use App\Core\Traits\Requests\WithOptionals;
use Illuminate\Foundation\Http\FormRequest;

final class ListEndpointRequest extends FormRequest
{
    use WithOptionals;
}
