<?php
declare(strict_types=1);

namespace ArsThanea\RemoteMediaBundle\MediaHandler\Imagine\Cache\Resolver;


use Liip\ImagineBundle\Binary\BinaryInterface;
use Liip\ImagineBundle\Imagine\Cache\Resolver\ResolverInterface;

final class PrefixResolver implements ResolverInterface
{
    /** @var ResolverInterface */
    private $resolver;

    /** @var string */
    private $prefix;

    /** @var string */
    private $cdnUrl;

    public function __construct(ResolverInterface $resolver, $prefix, $cdnUrl)
    {
        $this->resolver = $resolver;
        $this->prefix = $prefix;
        $this->cdnUrl = $cdnUrl;
    }

    public function resolve($path, $filter): string
    {
        $path = $this->rewritePath($path);
        $filteredUrl = $this->resolver->resolve($path, $filter);

        return $this->rewriteUrl($filteredUrl);
    }

    public function store(BinaryInterface $binary, $path, $filter): void
    {
        $path = $this->rewritePath($path);

        $this->resolver->store($binary, $path, $filter);
    }

    public function isStored($path, $filter): bool
    {
        $path = $this->rewritePath($path);

        return (bool)$this->resolver->isStored($path, $filter);
    }

    public function remove(array $paths, array $filters): void
    {
        $paths = \array_map(function ($path) {
            return $this->rewritePath($path);
        }, $paths);

        $this->resolver->remove($paths, $filters);
    }

    private function rewritePath(string $path): string
    {
        $path = \parse_url($path, PHP_URL_PATH);
        $path = \ltrim($path, '/');

        return $this->prefix . $path;
    }

    private function rewriteUrl(string $url): string
    {
        $path = \parse_url($url, PHP_URL_PATH);

        if ($path === $url) {
            return \str_replace($this->prefix, '', $path);
        }

        return $this->cdnUrl . $path;
    }

}
