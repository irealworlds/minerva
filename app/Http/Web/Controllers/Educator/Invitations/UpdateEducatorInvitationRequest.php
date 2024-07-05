<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Educator\Invitations;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateEducatorInvitationRequest extends FormRequest
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
