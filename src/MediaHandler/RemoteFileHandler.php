<?php
declare(strict_types=1);

namespace ArsThanea\RemoteMediaBundle\MediaHandler;


use ArsThanea\RemoteMediaBundle\MediaUrl\MediaUrl;
use Kunstmaan\MediaBundle\Entity\Media;
use Kunstmaan\MediaBundle\Helper\ExtensionGuesserFactoryInterface;
use Kunstmaan\MediaBundle\Helper\File\FileHandler;
use Kunstmaan\MediaBundle\Helper\MimeTypeGuesserFactoryInterface;
use Kunstmaan\UtilitiesBundle\Helper\SlugifierInterface;
use Symfony\Component\HttpFoundation\File\File;

class RemoteFileHandler extends FileHandler
{
    /** @var S3MediaUploader */
    private $uploader;

    /** @var SlugifierInterface */
    private $slugifier;

    public function __construct(
        $priority,
        MimeTypeGuesserFactoryInterface $mimeTypeGuesserFactory,
        ExtensionGuesserFactoryInterface $extensionGuesserFactory,
        S3MediaUploader $uploader,
        SlugifierInterface $slugifier
    ) {
        parent::__construct($priority, $mimeTypeGuesserFactory, $extensionGuesserFactory);
        $this->uploader = $uploader;
        $this->slugifier = $slugifier;
    }

    public function prepareMedia(Media $media): void
    {
        $url = new MediaUrl($media->getUrl());
        $media->setUuid(uniqid());

        parent::prepareMedia($media);

        if ($media->getContent() instanceof File) {
            // if media already has it’s local path ($url) then i don’t want parent to overwrite it

            $url->parseToPath();

            if ($url->isOriginal() && false === $url->isEndedWithDot()) {
                $media->setUrl($url->value());
            }

            $dirname = \dirname($media->getUrl());
            $ext = \pathinfo($media->getUrl(), PATHINFO_EXTENSION);
            $filename = $this->slugifier->slugify(\basename($media->getUrl(), $ext)) . ($ext ? ".$ext" : '');
            $customUrl = \implode('/', [$dirname, $filename]);

            $media->setUrl($this->uploader->getUploadsUrl() . $customUrl);
        }
    }

    public function getShowTemplate(Media $media): string
    {
        return 'RemoteMediaBundle:Media\File:show.html.twig';
    }

    public function saveMedia(Media $media): void
    {
        if ($media->getContent() instanceof File) {
            $this->uploader->uploadMedia($media);
        }
    }
}
