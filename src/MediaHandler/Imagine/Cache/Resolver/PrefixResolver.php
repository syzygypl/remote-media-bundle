<?php

namespace ArsThanea\RemoteMediaBundle\MediaHandler\Imagine\Cache\Resolver;

use Liip\ImagineBundle\Binary\BinaryInterface;
use Liip\ImagineBundle\Imagine\Cache\Resolver\ResolverInterface;

class PrefixResolver implements ResolverInterface
{
    /**
     * @var ResolverInterface
     */
    private $resolver;

    /**
     * @var string
     */
    private $prefix;

    /**
     * @var string
     */
    private $cdnUrl;

    public function __construct(ResolverInterface $resolver, $prefix, $cdnUrl)
    {
        $this->resolver = $resolver;
        $this->prefix = $prefix;
        $this->cdnUrl = $cdnUrl;
    }

    public function resolve($path, $filter)
    {
        $path = $this->rewritePath($path);

        $url = $this->resolver->resolve($path, $filter);

        $url = $this->rewriteUrl($url);

        return $url;
    }

    public function store(BinaryInterface $binary, $path, $filter)
    {
        $path = $this->rewritePath($path);

        $this->resolver->store($binary, $path, $filter);
    }

    public function isStored($path, $filter)
    {
        $path = $this->rewritePath($path);

        return $this->resolver->isStored($path, $filter);
    }

    public function remove(array $paths, array $filters)
    {
        $paths = array_map(function ($path) {
            return $this->rewritePath($path);
        }, $paths);

        $this->resolver->remove($paths, $filters);
    }

    private function rewritePath($path)
    {
        $path = parse_url($path, PHP_URL_PATH);
        $path = ltrim($path, '/');

        return $this->prefix . $path;
    }

    private function rewriteUrl($url)
    {
        $path = parse_url($url, PHP_URL_PATH);

        if ($path === $url) {
            return str_replace($this->prefix, '', $path);
        }

        return $this->cdnUrl . $path;
    }

}
