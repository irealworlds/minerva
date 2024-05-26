<?php

declare(strict_types=1);

namespace App\Http\Api\Requests\Institutions;

use App\Core\Traits\Requests\WithOptionals;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string|null $search
 */
final class InstitutionListRequest extends FormRequest
{
    use WithOptionals;
}
