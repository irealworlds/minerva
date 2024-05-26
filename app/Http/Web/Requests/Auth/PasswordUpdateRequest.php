<?php

declare(strict_types=1);

namespace App\Http\Web\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password as PasswordRule;

/**
 * @property string $current_password
 * @property string $password
 */
final class PasswordUpdateRequest extends FormRequest
{
    /**
     * @return array<string, array<int, PasswordRule|string|null>>
     */
    public function rules(): array
    {
        return [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', PasswordRule::defaults(), 'confirmed'],
        ];
    }
}
