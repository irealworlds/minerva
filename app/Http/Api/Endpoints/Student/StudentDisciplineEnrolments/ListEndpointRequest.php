<?php

declare(strict_types=1);

namespace App\Http\Api\Endpoints\Student\StudentDisciplineEnrolments;

use App\Core\Traits\Requests\WithOptionals;
use Illuminate\Foundation\Http\FormRequest;

final class ListEndpointRequest extends FormRequest
{
    use WithOptionals;

    /**
     * @return array{
     *   studentGroupKey: string[]
     * }
     */
    public function rules(): array
    {
        return [
            'studentGroupKey' => ['sometimes', 'required', 'string'],
        ];
    }
}
