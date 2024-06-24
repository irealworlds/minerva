<?php

declare(strict_types=1);

namespace App\Http\Api\Endpoints\Educator\StudentDisciplineEnrolments;

use App\Core\Traits\Requests\WithOptionals;
use Illuminate\Foundation\Http\FormRequest;

final class ListEndpointRequest extends FormRequest
{
    use WithOptionals;

    /**
     * @return array{
     *     disciplineKey: string[],
     *     studentGroupKey: string[],
     *     studentRegistrationKey: string[]
     * }
     */
    public function rules(): array
    {
        return [
            'disciplineKey' => ['sometimes', 'string'],
            'studentGroupKey' => ['sometimes', 'string'],
            'studentRegistrationKey' => ['sometimes', 'string'],
        ];
    }
}
