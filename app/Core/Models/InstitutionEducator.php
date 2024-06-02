<?php

declare(strict_types=1);

namespace App\Core\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, Pivot};

/**
 * @property string $id
 * @property mixed $institution_id
 * @property mixed $educator_id
 * @property iterable<string> $roles
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Institution $institution
 * @property-read Educator $educator
 */
class InstitutionEducator extends Pivot
{
    use HasUuids;

    protected $fillable = ['institution_id', 'educator_id', 'roles'];

    protected $table = 'institution_educators';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'roles' => 'array',
        ];
    }

    /**
     * @return BelongsTo<Institution, self>
     */
    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class, 'institution_id');
    }

    /**
     * @return BelongsTo<Educator, self>
     */
    public function educator(): BelongsTo
    {
        return $this->belongsTo(Educator::class, 'educator_id');
    }
}
