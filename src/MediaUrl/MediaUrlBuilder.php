<?php
/**
 * Created by PhpStorm.
 * User: freeq
 * Date: 06/12/2018
 * Time: 00:05
 */
declare(strict_types=1);

namespace ArsThanea\RemoteMediaBundle\MediaUrl;


final class MediaUrlBuilder
{
    /** @var string */
    private $cdnUrl;

    public function __construct(string $cdnUrl)
    {
        $this->cdnUrl = $cdnUrl;
    }

    public function build(string $baseUrl): MediaUrl
    {
        $url = new MediaUrl($baseUrl);
        $url->trim();

        if ($url->isEmpty()) {
            return $url;
        }

        $url->parseToPath();
        $url->withPrefix($this->cdnUrl);

        return $url;
    }
}
