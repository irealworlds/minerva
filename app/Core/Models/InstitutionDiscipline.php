<?php

declare(strict_types=1);

namespace App\Core\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property mixed $institution_id
 * @property mixed $discipline_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
final class InstitutionDiscipline extends Pivot
{
    protected $fillable = ['institution_id', 'discipline_id'];

    protected $table = 'institution_disciplines';
}
