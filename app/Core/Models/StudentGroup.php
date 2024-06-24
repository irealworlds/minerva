<?php

declare(strict_types=1);

namespace App\Core\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{
    BelongsTo,
    BelongsToMany,
    HasMany,
    HasManyThrough,
    MorphMany,
    MorphTo,
};
use Illuminate\Support\Enumerable;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

/**
 * @property string $id
 * @property string $name
 * @property class-string $parent_type
 * @property mixed $parent_id
 * @property-read mixed $parent_institution_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Model $parent
 * @property-read Institution $parentInstitution
 * @property-read Enumerable<int, StudentGroup> $childGroups
 * @property-read Enumerable<int, Discipline> $disciplines Disciplines studied by this student group.
 */
final class StudentGroup extends Model
{
    use HasFactory;
    use HasUuids;
    use HasRecursiveRelationships;

    protected $fillable = ['name', 'parent_type', 'parent_id'];

    /**
     * Get this student group's parent.
     *
     * @return MorphTo<Model, StudentGroup>
     */
    public function parent(): MorphTo
    {
        return $this->morphTo(type: 'parent_type', id: 'parent_id');
    }

    /**
     * @return HasMany<StudentGroup>
     */
    public function children(): HasMany
    {
        return $this->hasMany(StudentGroup::class, 'parent_id')->where(
            'parent_type',
            StudentGroup::class,
        );
    }

    /**
     * Get the groups subordinated to this student group.
     *
     * @deprecated Use {@link static::children()} instead.
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

    /**
     * @return BelongsTo<Institution, self>
     */
    public function parentInstitution(): BelongsTo
    {
        return $this->belongsTo(Institution::class, 'parent_institution_id');
    }

    /**
     * @return HasManyThrough<StudentRegistration>
     */
    public function studentRegistrations(): HasManyThrough
    {
        return $this->hasManyThrough(
            related: StudentRegistration::class,
            through: StudentGroupEnrolment::class,
            firstKey: (new StudentGroupEnrolment())
                ->studentGroup()
                ->getForeignKeyName(),
            secondKey: (new StudentRegistration())->getKeyName(),
            localKey: (new self())->getKeyName(),
            secondLocalKey: (new StudentGroupEnrolment())
                ->studentRegistration()
                ->getForeignKeyName(),
        );
    }

    /**
     * @return HasMany<StudentGroupDisciplineEducator>
     */
    public function disciplineEducators(): HasMany
    {
        return $this->hasMany(
            StudentGroupDisciplineEducator::class,
            (new StudentGroupDisciplineEducator())
                ->studentGroup()
                ->getForeignKeyName(),
        );
    }
}
