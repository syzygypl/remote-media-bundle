<?php

namespace ArsThanea\RemoteMediaBundle\MediaHandler\Imagine\Cache;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CacheManager extends \Liip\ImagineBundle\Imagine\Cache\CacheManager
{
    /**
     * @inheritDoc
     */
    public function generateUrl($path, $filter, array $runtimeConfig = [], $resolver = null, $referenceType = UrlGeneratorInterface::ABSOLUTE_URL)
    {
        $path = parse_url($path, PHP_URL_PATH);

        return parent::generateUrl($path, $filter, $runtimeConfig, $resolver);
    }


}
