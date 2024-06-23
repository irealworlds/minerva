<?php

declare(strict_types=1);

namespace App\Core\Traits\Media;

use App\Core\Models\Media;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @mixin Model
 */
trait InteractsWithMediaUsingNumericKey
{
    use InteractsWithMedia {
        InteractsWithMedia::media as parentMediaRelation;
    }

    /**
     * @return MorphMany<Media>
     */
    public function media(): MorphMany
    {
        // TODO remove this when https://github.com/spatie/laravel-medialibrary/issues/3653 is solved
        return $this->morphMany(
            $this->getMediaModel(),
            'model',
            localKey: (string) DB::raw('CAST(id AS VARCHAR)')->getValue(
                DB::getQueryGrammar(),
            ),
        );
    }
}
