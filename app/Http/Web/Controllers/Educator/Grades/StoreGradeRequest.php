<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Educator\Grades;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read string $studentDisciplineEnrolmentKey
 * @property-read string $studentGroupKey
 * @property-read string $disciplineKey
 * @property-read float $awardedPoints
 * @property-read float $maximumPoints
 * @property-read string|null $notes
 * @property-read string $awardedAt
 */
final class StoreGradeRequest extends FormRequest
{
    /**
     * @return array{
     *     studentDisciplineEnrolmentKey: string[],
     *     studentGroupKey: string[],
     *     disciplineKey: string[],
     *     awardedPoints: string[],
     *     maximumPoints: string[],
     *     notes: string[],
     *     awardedAt: string[]
     * }
     */
    public function rules(): array
    {
        return [
            'studentDisciplineEnrolmentKey' => ['required', 'string'],
            'studentGroupKey' => ['required', 'string'],
            'disciplineKey' => ['required', 'string'],
            'awardedPoints' => [
                'required',
                'numeric',
                'min:0',
                'lte:maximumPoints',
            ],
            'maximumPoints' => [
                'required',
                'numeric',
                'min:0',
                'gte:awardedPoints',
            ],
            'notes' => ['sometimes', 'nullable', 'string'],
            'awardedAt' => ['required', 'date'],
        ];
    }
}
