<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Admin\StudentEnrolments;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $studentKey
 * @property string $studentGroupKey
 * @property iterable<array{disciplineKey: string, educatorKey: string}> $disciplines
 */
final class StoreStudentEnrolmentRequest extends FormRequest
{
    /**
     * @return array{
     *     studentKey: string[],
     *     studentGroupKey: string[],
     *     disciplines: string[],
     *     "disciplines.*.disciplineKey": string[],
     *     "disciplines.*.educatorKey": string[]
     * }
     */
    public function rules(): array
    {
        return [
            'studentKey' => ['required', 'string'],
            'studentGroupKey' => ['required', 'string'],

            'disciplines' => ['required', 'array'],
            'disciplines.*.disciplineKey' => ['required', 'string'],
            'disciplines.*.educatorKey' => ['required', 'string'],
        ];
    }
}
