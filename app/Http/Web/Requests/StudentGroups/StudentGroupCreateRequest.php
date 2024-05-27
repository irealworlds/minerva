<?php

declare(strict_types=1);

namespace App\Http\Web\Requests\StudentGroups;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\In;

/**
 * @property "institution"|"studentGroup" $parentType
 * @property string $parentId
 * @property string $name
 */
final class StudentGroupCreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, (string|In)[]>
     */
    public function rules(): array
    {
        return [
            // Parent
            'parentType' => [
                'required',
                'string',
                new In(['institution', 'studentGroup']),
            ],
            'parentId' => ['required', 'string'],

            // Details
            'name' => ['required', 'string', 'max:64'],
        ];
    }
}
