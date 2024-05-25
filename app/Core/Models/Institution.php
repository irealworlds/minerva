<?php

declare(strict_types=1);

namespace App\Core\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{
    BelongsTo,
    HasMany,
    MorphMany};
use Illuminate\Support\Enumerable;
use Spatie\MediaLibrary\{
    HasMedia,
    InteractsWithMedia};

/**
 * @property string $id
 * @property string $name
 * @property string|null $parent_institution_id
 * @property string|null $website
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Institution|null $parent
 * @property-read Enumerable<Institution> $children
 */
class Institution extends Model implements HasMedia
{
    use HasUuids;
    use InteractsWithMedia;
    use HasFactory;

    public const EmblemPictureMediaCollection = 'emblem_picture';

    protected $fillable = [
        'name',
        'website',
        'parent_institution_id'
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::EmblemPictureMediaCollection)
            ->singleFile();
    }

    /**
     * Get the institution this entity is subordinated to.
     *
     * @return BelongsTo<Institution, Institution>
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Institution::class, 'parent_institution_id');
    }

    /**
     * Get this entity's child institutions.
     *
     * @return HasMany<Institution>
     */
    public function children(): HasMany
    {
        return $this->hasMany(Institution::class, 'parent_institution_id');
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
            (new StudentGroup())->parent()->getRelationName()
        );
    }
}
