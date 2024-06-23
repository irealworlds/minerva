<?php

declare(strict_types=1);

namespace App\Core\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property mixed $student_group_enrolment_id
 * @property mixed $discipline_id
 * @property mixed $educator_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read StudentGroupEnrolment $studentGroupEnrolment
 * @property-read Discipline $discipline
 * @property-read Educator $educator
 */
final class StudentDisciplineEnrolment extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'student_group_enrolment_id',
        'discipline_id',
        'educator_id',
    ];

    /**
     * @return BelongsTo<StudentGroupEnrolment, self>
     */
    public function studentGroupEnrolment(): BelongsTo
    {
        return $this->belongsTo(
            StudentGroupEnrolment::class,
            'student_group_enrolment_id',
        );
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
