<?php
declare(strict_types=1);

namespace ArsThanea\RemoteMediaBundle\MediaHandler\Imagine\Cache\Resolver;


use ArsThanea\RemoteMediaBundle\MediaUrl\MediaUrl;
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
        $url = new MediaUrl($path);
        $url->parseToPath();
        $url->trim();
        $url->addPrefix($this->prefix);

        return $url->value();
    }

    private function rewriteUrl(string $baseUrl): string
    {
        $url = new MediaUrl($baseUrl);
        $url->parseToPath();

        if ($url->isOriginal()) {
            return \str_replace($this->prefix, '', $url->value());
        }

        $url->addPrefix($this->cdnUrl);

        return $url->value();
    }

}
