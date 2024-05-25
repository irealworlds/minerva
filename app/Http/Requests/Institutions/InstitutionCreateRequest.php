<?php

declare(strict_types=1);

namespace App\Http\Requests\Institutions;

use App\Core\Models\Institution;
use App\Http\Rules\ExistsRouteKey;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

/**
 * @property string $name
 * @property string|null $website
 * @property UploadedFile|null $picture
 * @property string|null $parentInstitutionId
 */
class InstitutionCreateRequest extends FormRequest
{
    /**
     * Get a list of validation rules to apply to this request.
     *
     * @return array<string, array<string|ExistsRouteKey<Institution>>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:128'],
            'website' => ['sometimes', 'nullable', 'string', 'url', 'max:64'],
            'picture' => ['sometimes', 'nullable', 'image', 'max:1024'],
            'parentInstitutionId' => ['sometimes', 'nullable', new ExistsRouteKey(Institution::class)],
        ];
    }
}
