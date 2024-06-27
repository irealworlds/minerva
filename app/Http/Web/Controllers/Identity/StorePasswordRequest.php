<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Identity;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

/**
 * @property-read string $password
 */
final class StorePasswordRequest extends FormRequest
{
    /**
     * @return array{
     *     password: (string|Password|null)[],
     *     passwordConfirmation: string[]
     * }
     */
    public function rules(): array
    {
        return [
            'password' => ['required', 'string', Password::defaults()],
            'passwordConfirmation' => ['required', 'string', 'same:password'],
        ];
    }
}
