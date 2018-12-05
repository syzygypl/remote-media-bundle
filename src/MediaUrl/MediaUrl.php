<?php
/**
 * Created by PhpStorm.
 * User: freeq
 * Date: 06/12/2018
 * Time: 00:28
 */
declare(strict_types=1);

namespace ArsThanea\RemoteMediaBundle\MediaUrl;


final class MediaUrl
{
    private const DOT = '.';

    /** @var string */
    private $originalUrl;

    /** @var string */
    private $url;

    public function __construct(string $url)
    {
        $this->originalUrl = $url;
        $this->url = $url;
    }

    public function value(): string
    {
        return $this->url;
    }

    public function original(): string
    {
        return $this->originalUrl;
    }

    public function isEmpty(): bool
    {
        return empty($this->url);
    }

    public function isOriginal(): bool
    {
        return $this->originalUrl === $this->url;
    }

    public function isEndedWithDot(): bool
    {
        return self::DOT === \substr($this->url, -1);
    }

    public function trim(): void
    {
        $this->url = \trim($this->url, '/');
    }

    public function parseToPath(): void
    {
        $parsed = \parse_url($this->url, PHP_URL_PATH);
        $this->url = (string)$parsed;
    }

    public function withPrefix(string $prefix): void
    {
        $this->url = $prefix . $this->url;
    }
}
