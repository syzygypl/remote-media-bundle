<?php

namespace ArsThanea\RemoteMediaBundle\Service;

class UrlService implements UrlServiceInterface
{

    /**
     * @var string
     */
    private $mediaCdn;

    /**
     * @param string $mediaCdn
     */
    public function __construct($mediaCdn)
    {
        $this->mediaCdn = $mediaCdn;
    }

    public function getMediaUrl($url)
    {
        if ("" === trim($url, '/')) {
            return "";
        }

        $path = parse_url($url, PHP_URL_PATH);

        return $this->mediaCdn . $path;
    }

}
