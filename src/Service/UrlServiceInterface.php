<?php

namespace ArsThanea\RemoteMediaBundle\Service;

interface UrlServiceInterface
{

    /**
     * @param string $url
     *
     * @return string
     */
    public function getMediaUrl($url);
}
