<?php

declare(strict_types=1);

namespace App\Http\Requests\Institutions;

use App\Core\Traits\Requests\WithOptionals;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

/**
 * @property string $name
 * @property string|null $website
 * @property UploadedFile|null $picture
 */
class InstitutionPublicProfileUpdateRequest extends FormRequest
{
    use WithOptionals;

    /**
     * Get a list of validation rules to apply to this request.
     *
     * @return array<string, string[]>
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:128'],
            'website' => ['sometimes', 'nullable', 'string', 'url', 'max:64'],
            'picture' => ['sometimes', 'nullable', 'image', 'max:1024'],
        ];
    }
}
