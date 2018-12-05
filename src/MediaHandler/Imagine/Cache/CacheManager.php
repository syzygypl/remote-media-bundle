<?php
declare(strict_types=1);

namespace ArsThanea\RemoteMediaBundle\MediaHandler\Imagine\Cache;


use ArsThanea\RemoteMediaBundle\MediaUrl\MediaUrl;

final class CacheManager extends \Liip\ImagineBundle\Imagine\Cache\CacheManager
{
    /**
     * @inheritDoc
     */
    public function generateUrl($path, $filter, array $runtimeConfig = [], $resolver = null): string
    {
        $url = new MediaUrl($path);
        $url->parseToPath();

        return parent::generateUrl($url->value(), $filter, $runtimeConfig, $resolver);
    }
}
