<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Auth\AuthSessions;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read string $username
 * @property-read string $password
 */
class StoreAuthSessionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string[]>
     */
    public function rules(): array
    {
        return [
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
            'remember' => ['sometimes', 'boolean'],
        ];
    }
}
