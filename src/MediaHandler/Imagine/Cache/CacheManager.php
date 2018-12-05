<?php
declare(strict_types=1);

namespace ArsThanea\RemoteMediaBundle\MediaHandler\Imagine\Cache;


final class CacheManager extends \Liip\ImagineBundle\Imagine\Cache\CacheManager
{
    /**
     * @inheritDoc
     */
    public function generateUrl($path, $filter, array $runtimeConfig = [], $resolver = null): string
    {
        $path = \parse_url($path, PHP_URL_PATH);

        return parent::generateUrl($path, $filter, $runtimeConfig, $resolver);
    }
}
