<?php

declare(strict_types=1);

namespace App\Http\Api\Endpoints\Disciplines;

use App\Core\Traits\Requests\WithOptionals;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string|null $search
 * @property string|null $notAssociatedToInstitutionIds
 */
final class ListEndpointRequest extends FormRequest
{
    use WithOptionals;
}
