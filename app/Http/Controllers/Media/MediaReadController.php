<?php

declare(strict_types=1);

namespace App\Http\Controllers\Media;

use App\Http\Controllers\Controller;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\RouteAttributes\Attributes\Get;

class MediaReadController extends Controller
{
    #[Get('/Media/{media}', name: 'media.show', middleware: 'signed')]
    public function __invoke(Media $media): Media
    {
        return $media;
    }
}
