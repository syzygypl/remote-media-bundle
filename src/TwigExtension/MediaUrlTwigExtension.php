<?php
declare(strict_types=1);

namespace ArsThanea\RemoteMediaBundle\TwigExtension;


use ArsThanea\RemoteMediaBundle\MediaUrl\MediaUrlBuilder;

final class MediaUrlTwigExtension extends \Twig_Extension
{
    /** @var MediaUrlBuilder */
    private $mediaUrlBuilder;

    public function __construct(MediaUrlBuilder $mediaUrlBuilder)
    {
        $this->mediaUrlBuilder = $mediaUrlBuilder;
    }

    public function getFunctions(): array
    {
        return [
            'media_url' => new \Twig_SimpleFunction('media_url', [$this, 'buildMediaUrl']),
        ];
    }

    public function getName(): string
    {
        return 'remote_media.media_url';
    }

    public function buildMediaUrl($value): string
    {
        $mediaUrl = $this->mediaUrlBuilder->build($value);

        return $mediaUrl->value();
    }
}
