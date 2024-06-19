<?php

declare(strict_types=1);

namespace App\Core\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsToMany, HasMany, MorphMany};
use Illuminate\Support\Enumerable;
use Spatie\MediaLibrary\{HasMedia, InteractsWithMedia};
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

/**
 * @property string $id
 * @property string $name
 * @property string|null $parent_institution_id
 * @property string|null $website
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Institution|null $parent
 * @property-read Enumerable<Institution> $children
 * @property-read Enumerable<int, Discipline> $disciplines Disciplines offered at this institution.
 * @property-read Enumerable<int, Educator> $educators Educators registered at this institution.
 */
final class Institution extends Model implements HasMedia
{
    use HasUuids;
    use InteractsWithMedia;
    use HasFactory;
    use HasRecursiveRelationships;

    public const EmblemPictureMediaCollection = 'emblem_picture';

    protected $fillable = ['name', 'website', 'parent_institution_id'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(
            self::EmblemPictureMediaCollection,
        )->singleFile();
    }

    /** @inheritDoc */
    public function getParentKeyName(): string
    {
        return 'parent_institution_id';
    }

    /**
     * Get this institution's student groups.
     *
     * @return MorphMany<StudentGroup>
     */
    public function groups(): MorphMany
    {
        return $this->morphMany(
            StudentGroup::class,
            (new StudentGroup())->parent()->getRelationName(),
        );
    }

    /**
     * Get the disciplines offered at this institution.
     *
     * @return BelongsToMany<Discipline>
     */
    public function disciplines(): BelongsToMany
    {
        return $this->belongsToMany(
            Discipline::class,
            InstitutionDiscipline::class,
        )->withTimestamps();
    }

    /**
     * Get the educators registered at this institution.
     *
     * @return BelongsToMany<Educator>
     */
    public function educators(): BelongsToMany
    {
        return $this->belongsToMany(
            Educator::class,
            InstitutionEducator::class,
        )->withTimestamps();
    }

    /**
     * Get the invitations sent to educators to join this institution.
     *
     * @return HasMany<EducatorInvitation>
     */
    public function educatorInvitations(): HasMany
    {
        return $this->hasMany(EducatorInvitation::class);
    }
}
