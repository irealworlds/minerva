<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Educator\StudentGroups;

use App\Core\Traits\Requests\WithOptionals;
use Illuminate\Foundation\Http\FormRequest;

final class ListStudentGroupsRequest extends FormRequest
{
    use WithOptionals;

    /**
     * @return array{
     *     searchQuery: string[]
     * }
     */
    public function rules(): array
    {
        return [
            'searchQuery' => ['sometimes', 'string'],
        ];
    }
}
