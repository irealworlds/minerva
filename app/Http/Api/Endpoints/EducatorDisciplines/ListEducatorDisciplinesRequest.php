<?php

declare(strict_types=1);

namespace App\Http\Api\Endpoints\EducatorDisciplines;

use App\Core\Traits\Requests\WithOptionals;
use Illuminate\Foundation\Http\FormRequest;

final class ListEducatorDisciplinesRequest extends FormRequest
{
    use WithOptionals;

    /**
     * @return array{
     *     institutionKey: string[],
     * }
     */
    public function rules(): array
    {
        return [
            'institutionKey' => ['sometimes', 'required', 'string'],
        ];
    }
}
