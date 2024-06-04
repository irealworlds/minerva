<?php

declare(strict_types=1);

namespace App\Http\Web\Requests;

use App\Core\Traits\Requests\WithOptionals;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read string|null $namePrefix
 * @property-read string $firstName
 * @property-read string[] $middleNames
 * @property-read string $lastName
 * @property-read string|null $nameSuffix
 * @property-read string $email
 */
class ProfileUpdateRequest extends FormRequest
{
    use WithOptionals;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array{
     *      namePrefix: string[],
     *      firstName: string[],
     *      middleNames: string[],
     *      "middleNames.*": string[],
     *      lastName: string[],
     *      nameSuffix: string[],
     *      email: string[],
     *  }
     */
    public function rules(): array
    {
        return [
            'namePrefix' => ['sometimes', 'nullable', 'string', 'max:64'],
            'firstName' => ['sometimes', 'required', 'string', 'max:64'],
            'middleNames' => ['sometimes', 'array'],
            'middleNames.*' => ['sometimes', 'required', 'string', 'max:64'],
            'lastName' => ['sometimes', 'required', 'string', 'max:64'],
            'nameSuffix' => ['sometimes', 'nullable', 'string', 'max:64'],
            'email' => [
                'sometimes',
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
            ],
        ];
    }
}
