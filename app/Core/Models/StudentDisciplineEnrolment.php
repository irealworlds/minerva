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
 * @property mixed $student_registration_id
 * @property mixed $discipline_id
 * @property mixed $educator_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read StudentRegistration $studentRegistration
 * @property-read Discipline $discipline
 * @property-read Educator $educator
 */
class StudentDisciplineEnrolment extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'student_registration_id',
        'discipline_id',
        'educator_id',
    ];

    /**
     * @return BelongsTo<StudentRegistration, self>
     */
    public function studentRegistration(): BelongsTo
    {
        return $this->belongsTo(
            StudentRegistration::class,
            'student_registration_id',
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
