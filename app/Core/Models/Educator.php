<?php

declare(strict_types=1);

namespace App\Core\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany};
use Illuminate\Support\Enumerable;

/**
 * @property string $id
 * @property mixed $identity_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Identity $identity
 * @property-read Institution|null $pivot
 * @property-read Enumerable<int, Institution> $institutions The institutions this educator is associated with.
 */
class Educator extends Model
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
     * Get the institutions this educator is associated with.
     *
     * @return BelongsToMany<Institution>
     */
    public function institutions(): BelongsToMany
    {
        return $this->belongsToMany(
            Institution::class,
            InstitutionEducator::class,
        )->withTimestamps();
    }
}
