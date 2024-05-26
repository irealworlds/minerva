<?php

declare(strict_types=1);

namespace App\Core\Services;

use App\Http\Web\Controllers\Media\MediaReadController;
use DateTimeInterface;
use Exception;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Routing\UrlGenerator as AppUrlGenerator;
use Illuminate\Routing\{
    Route,
    Router};
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;
use Override;
use RuntimeException;
use Spatie\MediaLibrary\Conversions\Conversion;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;
use Spatie\MediaLibrary\Support\UrlGenerator\UrlGenerator;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

final class MediaUrlGenerator implements UrlGenerator
{
    protected ?Media $media = null;

    protected ?Conversion $conversion = null;

    protected ?PathGenerator $pathGenerator = null;

    public function __construct(
        private readonly AppUrlGenerator $_applicationUrlGenerator,
        private readonly Repository $_configuration,
        private readonly Router $_router
    ) {
    }

    /**
     * @throws RouteNotFoundException
     * @throws RuntimeException
     * @throws InvalidArgumentException
     */
    #[Override]
    public function getUrl(): string
    {
        if ($this->media === null) {
            throw new RuntimeException('Media entity not set on the generator.');
        }

        $route = $this->getMediaReadRoute();
        $routeName = $route->getName();

        if ($routeName === null) {
            throw new RuntimeException('No name set on the route for action [' . MediaReadController::class . '].');
        }

        $url = $this->_applicationUrlGenerator->signedRoute($routeName, [
            'media' => $this->media
        ]);

        return $this->versionUrl($url);
    }

    /**
     * @throws RuntimeException
     */
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

    /**
     * @inheritDoc
     *
     * @throws RuntimeException
     * @throws RouteNotFoundException
     */
    #[Override]
    public function getTemporaryUrl(DateTimeInterface $expiration, array $options = []): string
    {
        if ($this->media === null) {
            throw new RuntimeException('Media entity not set on the generator.');
        }

        $route = $this->getMediaReadRoute();
        $routeName = $route->getName();

        if ($routeName === null) {
            throw new RuntimeException('No name set on the route for action [' . MediaReadController::class . '].');
        }

        $url = $this->_applicationUrlGenerator->temporarySignedRoute($routeName, $expiration, [
            'media' => $this->media
        ]);

        return $this->versionUrl($url);
    }

    /**
     * @throws Exception
     */
    #[Override]
    public function getResponsiveImagesDirectoryUrl(): string
    {
        throw new Exception('Not implemented');
    }

    protected function versionUrl(string $path = ''): string
    {
        if (! $this->_configuration->get('media-library.version_urls')) {
            return $path;
        }

        return "$path?v={$this->media?->updated_at?->timestamp}";
    }

    /**
     * @throws RuntimeException
     */
    protected function getPathRelativeToRoot(): string
    {
        $pathGenerator = $this->pathGenerator;
        if ($pathGenerator === null) {
            throw new RuntimeException('Path generator not set on instance of [' . MediaUrlGenerator::class . '].');
        }
        $media = $this->media;
        if ($media === null) {
            throw new RuntimeException('Media entity not set on instance of [' . MediaUrlGenerator::class . '].');
        }

        $conversion = $this->conversion;
        if ($conversion === null) {
            return $pathGenerator->getPath($media) . ($media->file_name);
        } else {
            return $pathGenerator->getPathForConversions($media)
                . $conversion->getConversionFile($media);
        }
    }

    protected function getRootOfDisk(): string
    {
        return $this->getDisk()->path('/');
    }

    protected function getDisk(): Filesystem
    {
        return Storage::disk($this->getDiskName());
    }

    protected function getDiskName(): string|null
    {
        return $this->conversion === null
            ? $this->media?->disk
            : $this->media?->conversions_disk;
    }

    /**
     * @throws RouteNotFoundException
     * @throws RuntimeException
     */
    protected function getMediaReadRoute(): Route
    {
        $route = $this->_router->getRoutes()->getByAction(MediaReadController::class)
            ?? throw new RouteNotFoundException('No route registered for action [' . MediaReadController::class . '].');

        if (!$route->getName()) {
            throw new RuntimeException('No name set on the route for action [' . MediaReadController::class . '].');
        }

        return $route;
    }
}
