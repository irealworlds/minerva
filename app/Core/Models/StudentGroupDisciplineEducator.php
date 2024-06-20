<?php

declare(strict_types=1);

namespace App\Core\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, Pivot};

/**
 * @property mixed $student_group_id
 * @property mixed $discipline_id
 * @property mixed $educator_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read StudentGroup $studentGroup
 * @property-read Discipline $discipline
 * @property-read Educator $educator
 */
final class StudentGroupDisciplineEducator extends Pivot
{
    protected $fillable = ['student_group_id', 'discipline_id', 'educator_id'];

    protected $table = 'student_group_discipline_educators';

    /**
     * @return BelongsTo<StudentGroup, self>
     */
    public function studentGroup(): BelongsTo
    {
        return $this->belongsTo(StudentGroup::class, 'student_group_id');
    }

    /**
     * @return BelongsTo<Discipline, self>
     */
    public function discipline(): BelongsTo
    {
        return $this->belongsTo(Discipline::class, 'discipline_id');
    }

    /**
     * @return BelongsTo<Educator, self>
     */
    public function educator(): BelongsTo
    {
        return $this->belongsTo(Educator::class, 'educator_id');
    }
}
