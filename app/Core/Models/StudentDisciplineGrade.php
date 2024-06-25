<?php

namespace App\Core\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property mixed $student_id
 * @property mixed $student_group_id
 * @property mixed $discipline_id
 * @property float $awarded_points
 * @property float $maximum_points
 * @property string $notes
 * @property mixed $educator_id
 * @property Carbon $awarded_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Educator $educator
 * @property-read Discipline $discipline
 */
final class StudentDisciplineGrade extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'student_id',
        'student_group_id',
        'discipline_id',
        'awarded_points',
        'maximum_points',
        'notes',
        'educator_id',
        'awarded_at',
    ];

    protected $casts = [
        'awarded_at' => 'datetime:Y-m-d',
        'awarded_points' => 'float',
        'maximum_points' => 'float',
    ];

    /**
     * @return BelongsTo<StudentRegistration, self>
     */
    public function studentRegistration(): BelongsTo
    {
        return $this->belongsTo(StudentRegistration::class, 'student_id');
    }

    /**
     * @return BelongsTo<Discipline, self>
     */
    public function discipline(): BelongsTo
    {
        return $this->belongsTo(Discipline::class, 'discipline_id');
    }

    /**
     * @return BelongsTo<StudentGroup, self>
     */
    public function studentGroup(): BelongsTo
    {
        return $this->belongsTo(StudentGroup::class, 'student_group_id');
    }

    /**
     * @return BelongsTo<Educator, self>
     */
    public function educator(): BelongsTo
    {
        return $this->belongsTo(Educator::class, 'educator_id');
    }
}
