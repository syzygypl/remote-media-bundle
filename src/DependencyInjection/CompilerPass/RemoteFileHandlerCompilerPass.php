<?php

namespace ArsThanea\RemoteMediaBundle\DependencyInjection\CompilerPass;

use ArsThanea\RemoteMediaBundle\MediaHandler\RemoteFileHandler;
use ArsThanea\RemoteMediaBundle\MediaHandler\RemoteImageHandler;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class RemoteFileHandlerCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $container->getDefinition('kunstmaan_media.media_handlers.file')
            ->setClass(RemoteFileHandler::class)
            ->addArgument(new Reference('ars_thanea.remote_media.media_handler.uploader'))
            ->addArgument(new Reference('kunstmaan_utilities.slugifier'));

        $container->getDefinition('kunstmaan_media.media_handlers.image')
            ->setClass(RemoteImageHandler::class)
            ->replaceArgument(3, new Reference('ars_thanea.remote_media.media_handler.uploader'))
            ->addArgument(new Reference('kunstmaan_utilities.slugifier'));
    }
}
