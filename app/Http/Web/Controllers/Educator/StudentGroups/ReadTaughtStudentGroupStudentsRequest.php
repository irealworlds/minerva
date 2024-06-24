<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Educator\StudentGroups;

use App\Core\Traits\Requests\WithOptionals;
use Illuminate\Foundation\Http\FormRequest;

final class ReadTaughtStudentGroupStudentsRequest extends FormRequest
{
    use WithOptionals;

    /**
     * @return array{
     *     disciplineKey: string[]
     * }
     */
    public function rules(): array
    {
        return [
            'disciplineKey' => ['sometimes', 'required'],
        ];
    }
}
