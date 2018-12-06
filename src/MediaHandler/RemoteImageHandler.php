<?php
declare(strict_types=1);

namespace ArsThanea\RemoteMediaBundle\MediaHandler;


use ArsThanea\RemoteMediaBundle\MediaUrl\MediaUrl;
use Kunstmaan\MediaBundle\Entity\Media;
use Kunstmaan\MediaBundle\Helper\ExtensionGuesserFactoryInterface;
use Kunstmaan\MediaBundle\Helper\MimeTypeGuesserFactoryInterface;
use Kunstmaan\UtilitiesBundle\Helper\SlugifierInterface;
use Symfony\Component\HttpFoundation\File\File;

class RemoteImageHandler extends RemoteFileHandler
{
    /** @var string */
    private $aviaryApiKey;

    public function __construct(
        $priority,
        MimeTypeGuesserFactoryInterface $mimeTypeGuesserFactory,
        ExtensionGuesserFactoryInterface $extensionGuesserFactory,
        $aviaryApiKey,
        S3MediaUploader $uploader,
        SlugifierInterface $slugifier
    ) {
        parent::__construct($priority, $mimeTypeGuesserFactory, $extensionGuesserFactory, $uploader, $slugifier);
        $this->aviaryApiKey = $aviaryApiKey;
    }

    public function getAviaryApiKey(): string
    {
        return $this->aviaryApiKey;
    }

    public function getName(): string
    {
        return 'Image Handler';
    }

    public function getType(): string
    {
        return 'image';
    }

    /**
     * @param mixed $object
     *
     * @return bool
     */
    public function canHandle($object): bool
    {
        if (false === parent::canHandle($object)) {
            return false;
        }

        if ($object instanceof File) {
            return true;
        }

        return 0 === \strpos($object->getContentType(), 'image');
    }

    /**
     * {@inheritDoc}
     */
    public function getShowTemplate(Media $media): string
    {
        return 'KunstmaanMediaBundle:Media\Image:show.html.twig';
    }

    /**
     * @param Media  $media    The media entity
     * @param string $basePath The base path
     *
     * @return string
     */
    public function getImageUrl(Media $media, $basePath): string
    {
        $url = new MediaUrl($media->getUrl());
        $url->parseToPath();

        if ($url->isOriginal()) {
            $url->addPrefix($basePath);

            return $url->value();
        }

        return $url->original();
    }

    public function prepareMedia(Media $media): void
    {
        parent::prepareMedia($media);

        if ($media->getContent()) {
            [$width, $height] = \getimagesize($media->getContent());

            $media
                ->setMetadataValue('original_width', $width)
                ->setMetadataValue('original_height', $height);
        }
    }
}
