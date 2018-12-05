<?php
declare(strict_types=1);

namespace ArsThanea\RemoteMediaBundle\DependencyInjection;


use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

final class RemoteMediaExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $data = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('remote_media.yml');

        $cdnBucket = $data['cdn']['s3']['bucket'];
        $cdnRegion = $data['cdn']['s3']['region'];
        $cachePrefix = $data['cdn']['cache_prefix'];

        $container->setParameter('remote_media.cdn.s3.bucket', $cdnBucket);
        $container->setParameter('remote_media.cdn.s3.region', $cdnRegion);
        $container->setParameter('remote_media.cdn.s3.access_key', $data['cdn']['s3']['access_key']);
        $container->setParameter('remote_media.cdn.s3.access_secret', $data['cdn']['s3']['access_secret']);

        $mediaUrl = $data['cdn']['media_url'];

        if (null === $mediaUrl) {
            $mediaUrl = \sprintf('https://%s.s3-%s.amazonaws.com', $cdnBucket, $cdnRegion);
        }

        $container->setParameter('remote_media.cdn.media_url', $mediaUrl);

        if (null === $cachePrefix) {
            $env = $container->getParameter('kernel.environment');
            $cachePrefix = 'prod' === $env ? '' : "$env/";
        }

        $container->setParameter('remote_media.cdn.cache_prefix', $cachePrefix);

        if (isset($data['cdn']['cache_provider'])) {
            $this->setupCacheProvider($data['cdn']['cache_provider'], $container);
        }
    }

    private function setupCacheProvider($providerName, ContainerBuilder $container): void
    {
        $providerId = \sprintf('doctrine_cache.providers.%s', $providerName);

        $prefixResolverId = 'ars_thanea.remote_media.imagine.cache_resolver.prefix_resolver';
        $cacheId = 'ars_thanea.remote_media.imagine.cache.resolver.redis_cache';
        $cacheClass = $container->getParameter('ars_thanea.remote_media.imagine.cache.resolver.redis_cache.class');

        $s3Resolver = $container->getDefinition($prefixResolverId)->getArgument(0);

        $config = [
            'global_prefix' => 'liip',
            'prefix' => 'imagine',
        ];

        $definition = new Definition($cacheClass, [new Reference($providerId), $s3Resolver, $config]);
        $container->setDefinition($cacheId, $definition->setPublic(false));

        $container->getDefinition($prefixResolverId)->replaceArgument(0, new Reference($cacheId));
    }
}
