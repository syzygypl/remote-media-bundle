<?php
declare(strict_types=1);

namespace ArsThanea\RemoteMediaBundle\TwigExtension;


use ArsThanea\RemoteMediaBundle\MediaUrl\MediaUrl;

final class MediaUrlTwigExtension extends \Twig_Extension
{
    /** @var string */
    private $cdnUrl;

    public function __construct(string $cdnUrl)
    {
        $this->cdnUrl = $cdnUrl;
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

    public function buildMediaUrl($baseUrl): string
    {
        $url = new MediaUrl($baseUrl);
        $url->trim();

        if ($url->isEmpty()) {
            return $url->value();
        }

        $url->parseToPath();
        $url->addPrefix($this->cdnUrl);

        return $url->value();
    }
}
