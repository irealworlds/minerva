<?php

declare(strict_types=1);

namespace App\Core\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $id
 * @property mixed $student_group_id
 * @property mixed $student_registration_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read StudentGroup $studentGroup
 * @property-read StudentRegistration $studentRegistration
 */
class StudentGroupEnrolment extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = ['student_group_id', 'student_registration_id'];

    /**
     * @return BelongsTo<StudentGroup, self>
     */
    public function studentGroup(): BelongsTo
    {
        return $this->belongsTo(
            related: StudentGroup::class,
            foreignKey: 'student_group_id',
        );
    }

    /**
     * @return BelongsTo<StudentRegistration, self>
     */
    public function studentRegistration(): BelongsTo
    {
        return $this->belongsTo(
            related: StudentRegistration::class,
            foreignKey: 'student_registration_id',
        );
    }

    /**
     * @return HasMany<StudentDisciplineEnrolment>
     */
    public function disciplineEnrolments(): HasMany
    {
        return $this->hasMany(
            related: StudentDisciplineEnrolment::class,
            foreignKey: (new StudentDisciplineEnrolment())
                ->studentGroupEnrolment()
                ->getForeignKeyName(),
        );
    }
}
