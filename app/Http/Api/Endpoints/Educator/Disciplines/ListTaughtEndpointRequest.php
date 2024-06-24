<?php

declare(strict_types=1);

namespace App\Http\Api\Endpoints\Educator\Disciplines;

use App\Core\Traits\Requests\WithOptionals;
use Illuminate\Foundation\Http\FormRequest;

final class ListTaughtEndpointRequest extends FormRequest
{
    use WithOptionals;

    /**
     * @return array{
     *     studentGroupKey: string[],
     *     disciplineKey: string[]
     * }
     */
    public function rules(): array
    {
        return [
            'studentGroupKey' => ['sometimes', 'required', 'string', 'max:255'],
            'disciplineKey' => ['sometimes', 'required', 'string', 'max:255'],
        ];
    }
}
