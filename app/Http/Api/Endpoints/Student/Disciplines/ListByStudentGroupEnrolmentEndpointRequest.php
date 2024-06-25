<?php

declare(strict_types=1);

namespace App\Http\Api\Endpoints\Student\Disciplines;

use App\Core\Traits\Requests\WithOptionals;
use Illuminate\Foundation\Http\FormRequest;

final class ListByStudentGroupEnrolmentEndpointRequest extends FormRequest
{
    use WithOptionals;

    /**
     * @return array{
     *   searchQuery: string[]
     * }
     */
    public function rules(): array
    {
        return [
            'searchQuery' => ['sometimes', 'required', 'string'],
        ];
    }
}
