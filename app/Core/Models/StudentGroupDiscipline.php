<?php

declare(strict_types=1);

namespace App\Core\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property mixed $student_group_id
 * @property mixed $discipline_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
final class StudentGroupDiscipline extends Pivot
{
    protected $fillable = ['student_group_id', 'discipline_id'];

    protected $table = 'student_group_disciplines';
}
