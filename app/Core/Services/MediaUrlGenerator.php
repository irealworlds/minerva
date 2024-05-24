<?php

namespace App\Core\Services;

use App\Http\Controllers\Media\MediaReadController;
use DateTimeInterface;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Routing\UrlGenerator as AppUrlGenerator;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Override;
use RuntimeException;
use Spatie\MediaLibrary\Conversions\Conversion;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;
use Spatie\MediaLibrary\Support\UrlGenerator\UrlGenerator;
use UnexpectedValueException;

final class MediaUrlGenerator implements UrlGenerator
{
    protected ?Media $media = null;
    protected ?Conversion $conversion = null;

    protected ?PathGenerator $pathGenerator = null;

    function __construct(
        private readonly AppUrlGenerator $_applicationUrlGenerator,
        private readonly Repository      $_configuration,
        private readonly Router          $_router
    ) {
    }

    #[Override]
    public function getUrl(): string
    {
        if ($this->media === null) {
            throw new UnexpectedValueException("Media entity not set on the generator.");
        }

        $route = $this->getMediaReadRoute();
        $url = $this->_applicationUrlGenerator->signedRoute($route->getName(), [
            "media" => $this->media
        ]);

        return $this->versionUrl($url);
    }

    #[Override]
    public function getPath(): string
    {
        return $this->getRootOfDisk() . $this->getPathRelativeToRoot();
    }

    #[Override]
    public function setMedia(Media $media): MediaUrlGenerator
    {
        $this->media = $media;
        return $this;
    }

    #[Override]
    public function setConversion(Conversion $conversion): MediaUrlGenerator
    {
        $this->conversion = $conversion;
        return $this;
    }

    #[Override]
    public function setPathGenerator(PathGenerator $pathGenerator): UrlGenerator
    {
        $this->pathGenerator = $pathGenerator;
        return $this;
    }

    /** @inheritDoc */
    #[Override]
    public function getTemporaryUrl(DateTimeInterface $expiration, array $options = []): string
    {
        if ($this->media === null) {
            throw new UnexpectedValueException("Media entity not set on the generator.");
        }

        $route = $this->getMediaReadRoute();
        $url = $this->_applicationUrlGenerator->temporarySignedRoute($route->getName(), $expiration, [
            "media" => $this->media
        ]);

        return $this->versionUrl($url);
    }

    #[Override]
    public function getResponsiveImagesDirectoryUrl(): string
    {
        $path = $this->pathGenerator->getPathForResponsiveImages($this->media);

        return Str::finish($this->getDisk()->url($path), '/');
    }

    protected function versionUrl(string $path = ''): string
    {
        if (! $this->_configuration->get('media-library.version_urls')) {
            return $path;
        }

        return "$path?v={$this->media->updated_at->timestamp}";
    }

    protected function getPathRelativeToRoot(): string
    {
        if (is_null($this->conversion)) {
            return $this->pathGenerator->getPath($this->media).($this->media->file_name);
        }

        return $this->pathGenerator->getPathForConversions($this->media)
            .$this->conversion->getConversionFile($this->media);
    }

    protected function getRootOfDisk(): string
    {
        return $this->getDisk()->path('/');
    }

    protected function getDisk(): Filesystem
    {
        return Storage::disk($this->getDiskName());
    }

    protected function getDiskName(): string
    {
        return $this->conversion === null
            ? $this->media->disk
            : $this->media->conversions_disk;
    }

    protected function getMediaReadRoute(): Route
    {
        $route = $this->_router->getRoutes()->getByAction(MediaReadController::class)
            ?? throw new RuntimeException("No route registered for action [" . MediaReadController::class . "].");

        if (!$route->getName())
            throw new UnexpectedValueException("No name set on the route for action [" . MediaReadController::class . "].");

        return $route;
    }
}
