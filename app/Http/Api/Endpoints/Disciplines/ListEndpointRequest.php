<?php

declare(strict_types=1);

namespace App\Http\Api\Endpoints\Disciplines;

use App\Core\Traits\Requests\WithOptionals;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string|null $search
 * @property string|null $associatedToInstitutionIds
 * @property string|null $notAssociatedToInstitutionIds
 * @property string|null $associatedToStudentGroupIds
 * @property string|null $notAssociatedToStudentGroupIds
 */
final class ListEndpointRequest extends FormRequest
{
    use WithOptionals;
}
