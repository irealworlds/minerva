<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\EducatorDisciplines;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read string $studentGroupKey
 * @property-read string[] $disciplineKeys
 */
final class StoreEducatorDisciplinesRequest extends FormRequest
{
    /**
     * @return array{
     *     studentGroupKey: string[],
     *     disciplineKeys: string[],
     *     "disciplineKeys.*": string[]
     * }
     */
    public function rules(): array
    {
        return [
            'studentGroupKey' => ['required', 'string'],
            'disciplineKeys' => ['required', 'array'],
            'disciplineKeys.*' => ['required', 'string'],
        ];
    }
}
