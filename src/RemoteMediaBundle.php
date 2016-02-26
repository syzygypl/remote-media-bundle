<?php

namespace ArsThanea\RemoteMediaBundle;

use ArsThanea\RemoteMediaBundle\DependencyInjection\CompilerPass\RemoteFileHandlerCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class RemoteMediaBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RemoteFileHandlerCompilerPass);
    }

    public function getParent()
    {
        return 'KunstmaanMediaBundle';
    }

}
