<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Admin\Institutions;

use App\Core\Traits\Requests\WithOptionals;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string|null $search
 */
class InstitutionListRequest extends FormRequest
{
    use WithOptionals;
}
