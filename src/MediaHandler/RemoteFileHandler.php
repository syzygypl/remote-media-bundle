<?php

namespace ArsThanea\RemoteMediaBundle\MediaHandler;

use Kunstmaan\MediaBundle\Entity\Media;
use Kunstmaan\MediaBundle\Helper\File\FileHandler;
use Kunstmaan\UtilitiesBundle\Helper\SlugifierInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Mime\MimeTypesInterface;

class RemoteFileHandler extends FileHandler
{

    /**
     * @var S3MediaUploader
     */
    private $uploader;

    /**
     * @var SlugifierInterface
     */
    private $slugifier;

    public function __construct(
        $priority,
        MimeTypesInterface $mimeTypeGuesserFactory,
        S3MediaUploader $uploader,
        SlugifierInterface $slugifier
    ) {
        parent::__construct($priority, $mimeTypeGuesserFactory);
        $this->uploader = $uploader;
        $this->slugifier = $slugifier;
    }

    public function prepareMedia(Media $media)
    {
        $url = $media->getUrl();
        $media->setUuid(uniqid());
        parent::prepareMedia($media);

        if ($media->getContent() instanceof File) {
            // if media already has it’s local path ($url) then i don’t want parent to overwrite it

            if ($url && "." !== $url[strlen($url) - 1] && $url === parse_url($url, PHP_URL_PATH)) {
                $media->setUrl($url);
            }

            $dirname = dirname($media->getUrl());
            $ext = pathinfo($media->getUrl(), PATHINFO_EXTENSION);
            $filename = $this->slugifier->slugify(basename($media->getUrl(), $ext)) . ($ext ? ".$ext" : "");
            $url = implode('/', [$dirname, $filename]);
            $this->setMediaUrl($media, $url);

        }

    }

    public function getShowTemplate(Media $media)
    {
        return 'RemoteMediaBundle:Media\File:show.html.twig';
    }


    /**
     * @param Media  $media
     * @param string $url
     */
    public function setMediaUrl(Media $media, $url)
    {
        $media->setUrl($this->uploader->getUploadsUrl() . $url);
    }

    /**
     * @param Media $media
     *
     * @return void
     */
    public function saveMedia(Media $media)
    {
        if (!$media->getContent() instanceof File) {
            return;
        }

        $this->uploader->uploadMedia($media);
    }

}
