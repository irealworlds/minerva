<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Admin\EducatorInvitations;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $institutionKey
 * @property string $email
 * @property string[] $roles
 */
final class CreateEducatorInvitationRequest extends FormRequest
{
    /**
     * @return array{
     *     institutionKey: string[],
     *     email: string[],
     *     roles: string[],
     *     "roles.*": string[]
     * }
     */
    public function rules(): array
    {
        return [
            'institutionKey' => ['required', 'string'],
            'email' => ['required', 'email'],
            'roles' => ['required', 'array'],
            'roles.*' => ['required', 'string', 'max:64'],
        ];
    }
}
