<?php

namespace ArsThanea\RemoteMediaBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $root = $treeBuilder->root('remote_media');

        $cdnNode = $root->children()->arrayNode('cdn');
        $cdnNode->isRequired();
        $cdnNode->children()->scalarNode('media_url')->defaultNull();
        $cdnNode->children()->scalarNode('cache_prefix')->defaultNull();
        $cdnNode->children()->scalarNode('cache_provider')->defaultNull();

        $s3 = $cdnNode->children()->arrayNode('s3');
        $s3->isRequired();
        $s3->children()->scalarNode('bucket')->isRequired();
        $s3->children()->scalarNode('access_key')->isRequired();
        $s3->children()->scalarNode('access_secret')->isRequired();
        $s3->children()->scalarNode('region')->defaultValue('eu-west-1');

        return $treeBuilder;
    }
}
