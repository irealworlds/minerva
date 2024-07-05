<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Admin\StudentGroups;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string[] $disciplineKeys
 */
final class AddStudentGroupDisciplinesRequest extends FormRequest
{
    /**
     * @return array{
     *     disciplineKeys: string[],
     *     "disciplineKeys.*": string[]
     * }
     */
    public function rules(): array
    {
        return [
            'disciplineKeys' => ['required', 'array'],
            'disciplineKeys.*' => ['required', 'string'],
        ];
    }
}
