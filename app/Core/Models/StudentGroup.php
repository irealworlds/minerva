<?php

declare(strict_types=1);

namespace App\Core\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsToMany, MorphMany, MorphTo};
use Illuminate\Support\Enumerable;

/**
 * @property string $id
 * @property string $name
 * @property class-string $parent_type
 * @property mixed $parent_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Model $parent
 * @property-read Enumerable<int, StudentGroup> $childGroups
 * @property-read Enumerable<int, Discipline> $disciplines Disciplines studied by this student group.
 */
class StudentGroup extends Model
{
    use HasUuids;

    protected $fillable = ['name', 'parent_type', 'parent_id'];

    /**
     * Get this student group's parent.
     *
     * @return MorphTo<Model, StudentGroup>
     */
    public function parent(): MorphTo
    {
        return $this->morphTo('parent');
    }

    /**
     * Get the groups subordinated to this student group.
     *
     * @return MorphMany<StudentGroup>
     */
    public function childGroups(): MorphMany
    {
        return $this->morphMany(
            StudentGroup::class,
            (new StudentGroup())->parent()->getRelationName(),
        );
    }

    /**
     * Get the disciplines studied by this student group.
     *
     * @return BelongsToMany<Discipline>
     */
    public function disciplines(): BelongsToMany
    {
        return $this->belongsToMany(
            Discipline::class,
            StudentGroupDiscipline::class,
        )->withTimestamps();
    }
}
