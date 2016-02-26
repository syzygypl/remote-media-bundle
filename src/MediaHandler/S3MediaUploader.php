<?php

namespace ArsThanea\RemoteMediaBundle\MediaHandler;

use Aws\S3\S3Client;
use Kunstmaan\MediaBundle\Entity\Media;

class S3MediaUploader
{
    /**
     * @var S3Client
     */
    private $storage;

    private $bucketName;

    private $region;

    public function __construct(S3Client $storage, $bucketName, $region = 'eu-west-1')
    {
        $this->storage = $storage;
        $this->bucketName = $bucketName;
        $this->region = "s3-$region";
    }

    public function getUploadsUrl()
    {
        return sprintf('https://%s.%s.amazonaws.com', $this->bucketName, $this->region);
    }

    public function uploadMedia(Media $media, $acl = 'public-read')
    {
        $targetPath = parse_url($media->getUrl(), PHP_URL_PATH);

        if ($media->getUrl() === $targetPath) {
            throw new \InvalidArgumentException('Media has to have a public URL set before uploading');
        }

        if (null === $media->getContent()) {
            throw new \RuntimeException('Dunno how to get file contents');
        }

        $fd = fopen($media->getContent()->getRealPath(), 'rb');
        $storageResponse = $this->storage->putObject([
            'ACL'           => $acl,
            'Bucket'        => $this->bucketName,
            'Key'           => ltrim($targetPath, '/'),
            'Body'          => $fd,
            'ContentType'   => $media->getContentType(),
        ]);

        fclose($fd);

        return $storageResponse->get('ObjectURL');
    }

    public function exists(Media $media)
    {
        return $this->storage->doesObjectExist($this->bucketName, ltrim(parse_url($media->getUrl(), PHP_URL_PATH), '/'));
    }
}
