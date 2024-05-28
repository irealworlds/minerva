<?php

declare(strict_types=1);

namespace App\Http\Web\Requests\StudentGroups;

use App\Core\Traits\Requests\WithOptionals;
use Illuminate\Foundation\Http\FormRequest;

final class StudentGroupUpdateRequest extends FormRequest
{
    use WithOptionals;

    /**
     * @return array{name: string[]}
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:64'],
        ];
    }
}
