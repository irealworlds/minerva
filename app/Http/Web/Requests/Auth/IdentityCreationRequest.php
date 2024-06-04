<?php

declare(strict_types=1);

namespace App\Http\Web\Requests\Auth;

use App\Core\Models\Identity;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

/**
 * @property-read string|null $namePrefix
 * @property-read string $firstName
 * @property-read string[] $middleNames
 * @property-read string $lastName
 * @property-read string|null $nameSuffix
 * @property-read string $email
 * @property-read string $password
 */
final class IdentityCreationRequest extends FormRequest
{
    /**
     * @return array{
     *     namePrefix: string[],
     *     firstName: string[],
     *     middleNames: string[],
     *     "middleNames.*": string[],
     *     lastName: string[],
     *     nameSuffix: string[],
     *     email: string[],
     *     password: (string|Password|null)[],
     * }
     */
    public function rules(): array
    {
        return [
            'namePrefix' => ['sometimes', 'string', 'max:64'],
            'firstName' => ['required', 'string', 'max:64'],
            'middleNames' => ['required', 'array'],
            'middleNames.*' => ['sometimes', 'string', 'max:64'],
            'lastName' => ['required', 'string', 'max:64'],
            'nameSuffix' => ['sometimes', 'string', 'max:64'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:' . Identity::class,
            ],
            'password' => ['required', 'confirmed', Password::defaults()],
        ];
    }
}
