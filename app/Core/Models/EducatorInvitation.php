<?php

declare(strict_types=1);

namespace App\Core\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property mixed $institution_id
 * @property mixed $invited_educator_id
 * @property string $inviter_name
 * @property string $inviter_email
 * @property mixed|null $inviter_id
 * @property bool $accepted
 * @property iterable<string> $roles
 * @property Carbon|null $responded_at
 * @property Carbon $expired_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Institution $institution
 * @property-read Educator $invitedEducator
 */
final class EducatorInvitation extends Model
{
    use HasUuids;

    protected $fillable = [
        'institution_id',
        'invited_educator_id',
        'inviter_name',
        'inviter_email',
        'inviter_id',
        'accepted',
        'responded_at',
        'expired_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'responded_at' => 'datetime',
            'expired_at' => 'datetime',
            'roles' => 'array',
        ];
    }

    /**
     * @return BelongsTo<Institution, static>
     */
    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    /**
     * @return BelongsTo<Educator, static>
     */
    public function invitedEducator(): BelongsTo
    {
        return $this->belongsTo(Educator::class, 'invited_educator_id');
    }

    /**
     * @return BelongsTo<Identity, static>
     */
    public function inviter(): BelongsTo
    {
        return $this->belongsTo(Identity::class, 'inviter_id');
    }
}
