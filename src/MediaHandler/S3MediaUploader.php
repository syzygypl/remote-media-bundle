<?php
declare(strict_types=1);

namespace ArsThanea\RemoteMediaBundle\MediaHandler;


use ArsThanea\RemoteMediaBundle\MediaUrl\MediaUrl;
use Aws\S3\S3Client;
use Kunstmaan\MediaBundle\Entity\Media;

final class S3MediaUploader
{
    private const REGION_DEFAULT = 'eu-west-1';
    private const REGION_FORMAT = 's3-%s';
    private const AMAZON_URL_FORMAT = 'https://%s.%s.amazonaws.com';

    /** @var S3Client */
    private $storage;

    /** @var string */
    private $bucketName;

    /** @var string */
    private $region;

    public function __construct(S3Client $storage, string $bucketName, string $region = self::REGION_DEFAULT)
    {
        $this->storage = $storage;
        $this->bucketName = $bucketName;
        $this->region = \sprintf(self::REGION_FORMAT, $region);
    }

    public function getUploadsUrl(): string
    {
        return \sprintf(self::AMAZON_URL_FORMAT, $this->bucketName, $this->region);
    }

    public function uploadMedia(Media $media, $acl = 'public-read')
    {
        $url = new MediaUrl($media->getUrl());
        $url->parseToPath();

        if ($url->isOriginal()) {
            throw new \InvalidArgumentException('Media has to have a public URL set before uploading');
        }

        if (null === $media->getContent()) {
            throw new \RuntimeException('Dunno how to get file contents');
        }

        $url->trim();
        $fd = \fopen($media->getContent()->getRealPath(), 'rb');
        $storageResponse = $this->storage->putObject([
            'ACL'           => $acl,
            'Bucket'        => $this->bucketName,
            'Key'           => $url->value(),
            'Body'          => $fd,
            'ContentType'   => $media->getContentType(),
            'ContentLength' => \filesize($media->getContent()->getRealPath()),
            'CacheControl'  => 'public, max-age=283824000',
            'Expires'       => \gmdate('D, d M Y H:i:s T', \strtotime('+9 years')),
        ]);

        \fclose($fd);

        return $storageResponse->get('ObjectURL');
    }

    public function exists(Media $media): bool
    {
        $url = new MediaUrl($media->getUrl());
        $url->parseToPath();
        $url->trim();

        return $this->storage->doesObjectExist($this->bucketName, $url->value());
    }
}
