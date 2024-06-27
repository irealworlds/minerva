<?php

declare(strict_types=1);

namespace App\Core\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

/**
 * @property string $id
 * @property mixed $identity_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Identity $identity
 */
class StudentRegistration extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = ['identity_id'];

    /**
     * The identity behind this educator profile.
     *
     * @return BelongsTo<Identity, self>
     */
    public function identity(): BelongsTo
    {
        return $this->belongsTo(Identity::class);
    }

    /**
     * Get the student group enrolments for this student registration.
     *
     * @return HasMany<StudentGroupEnrolment>
     */
    public function studentGroupEnrolments(): HasMany
    {
        return $this->hasMany(
            StudentGroupEnrolment::class,
            'student_registration_id',
        );
    }
}
