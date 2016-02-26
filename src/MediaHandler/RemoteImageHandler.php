<?php

namespace ArsThanea\RemoteMediaBundle\MediaHandler;

use Kunstmaan\MediaBundle\Entity\Media;
use Symfony\Component\HttpFoundation\File\File;

class RemoteImageHandler extends RemoteFileHandler
{

    /**
     * @return string
     */
    public function getAviaryApiKey()
    {
        return null;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Image Handler';
    }

    /**
     * @return string
     */
    public function getType()
    {
        return 'image';
    }

    /**
     * @param mixed $object
     *
     * @return bool
     */
    public function canHandle($object)
    {
        if (parent::canHandle($object) && ($object instanceof File || strpos($object->getContentType(), 'image') === 0)) {
            return true;
        }

        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function getShowTemplate(Media $media)
    {
        return 'KunstmaanMediaBundle:Media\Image:show.html.twig';
    }

    /**
     * @param Media  $media    The media entity
     * @param string $basepath The base path
     *
     * @return string
     */
    public function getImageUrl(Media $media, $basepath)
    {
        if ($media->getUrl() === parse_url($media->getUrl(), PHP_URL_PATH)) {
            return $basepath . $media->getUrl();
        }

        return $media->getUrl();
    }

    /**
     * @param Media $media
     */
    public function prepareMedia(Media $media)
    {
        parent::prepareMedia($media);

        if ($media->getContent()) {
            $imageInfo = getimagesize($media->getContent());
            $width = $imageInfo[0];
            $height = $imageInfo[1];

            $media
                ->setMetadataValue('original_width', $width)
                ->setMetadataValue('original_height', $height);
        }
    }
}
