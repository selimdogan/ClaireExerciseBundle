<?php

namespace SimpleIT\ClaireAppBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
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
        $rootNode = $treeBuilder->root('simple_it_claire_app');

        $rootNode
            ->children()
                ->scalarNode('host')->isRequired()->end()
                ->scalarNode('default_format')->defaultValue('json')->end()
            ->end();

        return $treeBuilder;
    }
}
