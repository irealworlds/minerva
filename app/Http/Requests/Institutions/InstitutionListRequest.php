<?php

namespace App\Http\Requests\Institutions;

use App\Core\Traits\Requests\WithOptionals;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string|null $search
 */
class InstitutionListRequest extends FormRequest
{
    use WithOptionals;
}
