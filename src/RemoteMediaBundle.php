<?php
declare(strict_types=1);

namespace ArsThanea\RemoteMediaBundle;


use ArsThanea\RemoteMediaBundle\DependencyInjection\CompilerPass\RemoteFileHandlerCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class RemoteMediaBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new RemoteFileHandlerCompilerPass);
    }

    public function getParent(): string
    {
        return 'KunstmaanMediaBundle';
    }
}
