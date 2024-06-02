<?php

declare(strict_types=1);

namespace App\Http\Web\Requests\EducatorInstitutions;

use Illuminate\Foundation\Http\FormRequest;

final class EducatorInvitationUpdateRequest extends FormRequest
{
    /**
     * @return array{
     *     accepted: string[]
     * }
     */
    public function rules(): array
    {
        return [
            'accepted' => ['required', 'boolean'],
        ];
    }
}
