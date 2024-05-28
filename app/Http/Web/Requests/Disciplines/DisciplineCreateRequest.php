<?php

declare(strict_types=1);

namespace App\Http\Web\Requests\Disciplines;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $name
 * @property string|null $abbreviation
 * @property iterable<string> $associatedInstitutionKeys
 */
final class DisciplineCreateRequest extends FormRequest
{
    /**
     * Validation rules that apply to the request.
     *
     * @return array{
     *     associatedInstitutionKeys: string[],
     *     "associatedInstitutionKeys.*": string[],
     *     name: string[]
     * }
     */
    public function rules(): array
    {
        return [
            // Associations
            'associatedInstitutionKeys' => ['sometimes', 'required', 'array'],
            'associatedInstitutionKeys.*' => ['required', 'string'],

            // Details
            'name' => ['required', 'string', 'max:64'],
            'abbreviation' => ['nullable', 'string', 'max:32'],
        ];
    }
}
