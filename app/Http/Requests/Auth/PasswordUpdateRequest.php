<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password as PasswordRule;

/**
 * @property string $current_password
 * @property string $password
 */
final class PasswordUpdateRequest extends FormRequest
{
    /**
     * @return array<string, array<integer, PasswordRule|string|null>>
     */
    public function rules(): array
    {
        return [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', PasswordRule::defaults(), 'confirmed'],
        ];
    }
}
