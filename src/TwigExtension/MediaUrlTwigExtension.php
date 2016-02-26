<?php

namespace ArsThanea\RemoteMediaBundle\TwigExtension;

use ArsThanea\RemoteMediaBundle\Service\UrlServiceInterface;

class MediaUrlTwigExtension extends \Twig_Extension
{
    private $urlService;

    /**
     * @param $urlService
     */
    public function __construct(UrlServiceInterface $urlService)
    {
        $this->urlService = $urlService;
    }

    public function getFunctions()
    {
        return [
            'media_url' => new \Twig_SimpleFunction('media_url', [$this->urlService, 'getMediaUrl']),
        ];
    }

    public function getName()
    {
        return 'remote_media.media_url';
    }


}
