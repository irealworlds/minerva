<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Educator\Grades;

use App\Core\Traits\Requests\WithOptionals;
use Illuminate\Foundation\Http\FormRequest;

final class CreateGradeRequest extends FormRequest
{
    use WithOptionals;

    /**
     * @return array{
     *     disciplineKey: string[],
     *     studentGroupKey: string[],
     *     studentKey: string[]
     * }
     */
    public function rules(): array
    {
        return [
            'disciplineKey' => ['sometimes', 'required', 'string'],
            'studentGroupKey' => ['sometimes', 'required', 'string'],
            'studentKey' => ['sometimes', 'required', 'string'],
        ];
    }
}
