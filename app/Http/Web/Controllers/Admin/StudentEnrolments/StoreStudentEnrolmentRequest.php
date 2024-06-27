<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Admin\StudentEnrolments;

use App\Core\Models\Identity;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string|null $studentKey
 * @property array{
 *      idNumber: string,
 *      namePrefix: string|null,
 *      firstName: string,
 *      middleNames: string[],
 *      lastName: string,
 *      nameSuffix: string|null,
 *      email: string,
 * }|null $newIdentity
 * @property string $studentGroupKey
 * @property iterable<array{disciplineKey: string, educatorKey: string}> $disciplines
 */
final class StoreStudentEnrolmentRequest extends FormRequest
{
    /**
     * @return array{
     *     studentKey: string[],
     *     newIdentity: string[],
     *     "newIdentity.idNumber": string[],
     *     "newIdentity.namePrefix": string[],
     *     "newIdentity.firstName": string[],
     *     "newIdentity.middleNames": string[],
     *     "newIdentity.middleNames.*": string[],
     *     "newIdentity.lastName": string[],
     *     "newIdentity.nameSuffix": string[],
     *     "newIdentity.email": string[],
     *     studentGroupKey: string[],
     *     disciplines: string[],
     *     "disciplines.*.disciplineKey": string[],
     *     "disciplines.*.educatorKey": string[]
     * }
     */
    public function rules(): array
    {
        return [
            'studentKey' => [
                'required_without:newIdentity',
                'nullable',
                'string',
            ],
            'newIdentity' => [
                'required_without:studentKey',
                'nullable',
                'array',
            ],
            'newIdentity.idNumber' => [
                'required',
                'string',
                'size:13',
                'regex:/^[0-9]+$/i',
            ],
            'newIdentity.namePrefix' => [
                'sometimes',
                'nullable',
                'string',
                'max:64',
            ],
            'newIdentity.firstName' => ['required', 'string', 'max:64'],
            'newIdentity.middleNames' => ['present', 'array'],
            'newIdentity.middleNames.*' => ['sometimes', 'string', 'max:64'],
            'newIdentity.lastName' => ['required', 'string', 'max:64'],
            'newIdentity.nameSuffix' => [
                'sometimes',
                'nullable',
                'string',
                'max:64',
            ],
            'newIdentity.email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:' . Identity::class . ',email',
            ],

            'studentGroupKey' => ['required', 'string'],

            'disciplines' => ['required', 'array'],
            'disciplines.*.disciplineKey' => ['required', 'string'],
            'disciplines.*.educatorKey' => ['required', 'string'],
        ];
    }
}
