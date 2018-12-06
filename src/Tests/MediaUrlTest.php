<?php
/**
 * Created by PhpStorm.
 * User: freeq
 * Date: 06/12/2018
 * Time: 01:11
 */
declare(strict_types=1);

namespace ArsThanea\RemoteMediaBundle\Tests;


use ArsThanea\RemoteMediaBundle\MediaUrl\MediaUrl;
use PHPUnit\Framework\TestCase;

final class MediaUrlTest extends TestCase
{
    /**
     * @test
     */
    public function it_is_empty_when_passed_empty_string(): void
    {
        $emptyUrl = '';

        $url = new MediaUrl($emptyUrl);

        $this->assertTrue($url->isEmpty());
    }

    /**
     * @test
     */
    public function it_is_original_when_not_manipulated(): void
    {
        $baseUrl = 'Hello!';

        $url = new MediaUrl($baseUrl);

        $this->assertTrue($url->isOriginal());
    }

    /**
     * @test
     */
    public function it_is_original_when_trimmed_does_not_affect(): void
    {
        $baseUrl = 'Hello!';

        $url = new MediaUrl($baseUrl);
        $url->trim();

        $this->assertEquals('Hello!', $url->value());
    }

    /**
     * @test
     */
    public function it_is_not_original_when_trimmed_does_affect(): void
    {
        $baseUrl = '/Hello/';

        $url = new MediaUrl($baseUrl);
        $url->trim();

        $this->assertFalse($url->isOriginal());
        $this->assertEquals('Hello', $url->value());
    }

    /**
     * @test
     */
    public function it_is_ended_with_dot_when_it_is_lol(): void
    {
        $baseUrl = 'hello.';

        $url = new MediaUrl($baseUrl);

        $this->assertTrue($url->isEndedWithDot());
    }

    /**
     * @test
     */
    public function it_is_ended_without_dot_when_it_is_not(): void
    {
        $baseUrl = 'hello';

        $url = new MediaUrl($baseUrl);

        $this->assertFalse($url->isEndedWithDot());
    }

    /**
     * @test
     */
    public function it_is_empty_when_parsed_public_url(): void
    {
        $baseUrl = 'http://arsthanea-test.dev';

        $url = new MediaUrl($baseUrl);
        $url->parseToPath();

        $this->assertEmpty($url->value());
        $this->assertTrue($url->isEmpty());
    }

    /**
     * @test
     */
    public function it_is_not_empty_when_parsed_local_url(): void
    {
        $baseUrl = 'arsthanea-test.dev';

        $url = new MediaUrl($baseUrl);
        $url->parseToPath();

        $this->assertEquals('arsthanea-test.dev', $url->value());
        $this->assertFalse($url->isEmpty());
    }

    /**
     * @test
     */
    public function it_stores_prefix_when_added(): void
    {
        $baseUrl = 'arsthanea-test.dev';

        $url = new MediaUrl($baseUrl);
        $url->addPrefix('hi.');

        $this->assertEquals('hi.arsthanea-test.dev', $url->value());
    }
}
