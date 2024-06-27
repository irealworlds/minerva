<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Admin\StudentGroupEnrolments;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read string $disciplineKey
 * @property-read string $educatorKey
 */
final class StoreStudentGroupEnrolmentDisciplineRequest extends FormRequest
{
    /**
     * @return array{
     *     disciplineKey: string[],
     *     educatorKey: string[]
     * }
     */
    public function rules(): array
    {
        return [
            'disciplineKey' => ['required', 'string'],
            'educatorKey' => ['required', 'string'],
        ];
    }
}
