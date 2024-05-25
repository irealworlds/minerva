<?php

declare(strict_types=1);

namespace App\Core\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{
    MorphMany,
    MorphTo};
use Illuminate\Support\Enumerable;

/**
 * @property string $id
 * @property string $name
 * @property class-string $parent_type
 * @property mixed $parent_id
 * @property-read Model $parent
 * @property-read Enumerable<int, StudentGroup> $childGroups
 */
class StudentGroup extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
        'parent_type',
        'parent_id'
    ];

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
     * Get this institution's student groups.
     *
     * @return MorphMany<StudentGroup>
     */
    public function childGroups(): MorphMany
    {
        return $this->morphMany(
            StudentGroup::class,
            (new StudentGroup())->parent()->getRelationName()
        );
    }
}
