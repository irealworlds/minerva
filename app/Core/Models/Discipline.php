<?php

declare(strict_types=1);

namespace App\Core\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Enumerable;

/**
 * @property string $id
 * @property string $name
 * @property string|null $abbreviation
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property InstitutionDiscipline|StudentGroupDiscipline|null $pivot The pivot used for getting the institution in this query.
 * @property-read Enumerable<int, Institution> $institutions Institutions that offer this discipline.
 * @property-read Enumerable<int, StudentGroup> $studentGroup Student groups that study this discipline.
 */
final class Discipline extends Model
{
    use HasUuids;
    use HasFactory;

    protected $fillable = ['name', 'abbreviation'];

    /**
     * Get the institutions that offer this discipline.
     *
     * @return BelongsToMany<Institution>
     */
    public function institutions(): BelongsToMany
    {
        return $this->belongsToMany(
            Institution::class,
            InstitutionDiscipline::class,
        )->withTimestamps();
    }

    /**
     * Get the student groups that study this discipline.
     *
     * @return BelongsToMany<StudentGroup>
     */
    public function studentGroups(): BelongsToMany
    {
        return $this->belongsToMany(
            StudentGroup::class,
            StudentGroupDiscipline::class,
        )->withTimestamps();
    }
}
