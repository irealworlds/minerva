<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Admin\InstitutionEducators\Roles;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read iterable<string> $roles
 */
final class CreateInstitutionEducatorRolesRequest extends FormRequest
{
    /**
     * @return array{
     *     roles: string[],
     *     "roles.*": string[]
     * }
     */
    public function rules(): array
    {
        return [
            'roles' => ['required', 'array'],
            'roles.*' => ['required', 'string', 'max:64'],
        ];
    }
}
