<?php

namespace Erichard\GlideBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('erichard_glide');

        $rootNode
            ->children()
                ->scalarNode('sign_key')->defaultValue(null)->end()
                ->arrayNode('presets')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('or')->defaultNull()->end()
                            ->scalarNode('crop')->defaultNull()->end()
                            ->integerNode('w')->defaultNull()->end()
                            ->integerNode('h')->defaultNull()->end()
                            ->scalarNode('fit')->defaultNull()->end()
                            ->integerNode('dpr')->defaultNull()->end()
                            ->integerNode('bri')->defaultNull()->end()
                            ->integerNode('con')->defaultNull()->end()
                            ->floatNode('gam')->defaultNull()->end()
                            ->integerNode('sharp')->defaultNull()->end()
                            ->integerNode('blur')->defaultNull()->end()
                            ->integerNode('pixel')->defaultNull()->end()
                            ->scalarNode('filt')->defaultNull()->end()
                            ->scalarNode('mark')->defaultNull()->end()
                            ->scalarNode('markw')->defaultNull()->end()
                            ->scalarNode('markh')->defaultNull()->end()
                            ->scalarNode('markx')->defaultNull()->end()
                            ->scalarNode('marky')->defaultNull()->end()
                            ->scalarNode('markpad')->defaultNull()->end()
                            ->scalarNode('markpos')->defaultNull()->end()
                            ->scalarNode('markalpha')->defaultNull()->end()
                            ->scalarNode('bg')->defaultNull()->end()
                            ->scalarNode('border')->defaultNull()->end()
                            ->integerNode('q')->defaultNull()->end()
                            ->scalarNode('fm')->defaultNull()->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('servers')
                    ->useAttributeAsKey('name')
                    ->requiresAtLeastOneElement()
                    ->prototype('array')
                        ->children()
                            ->scalarNode('source')->isRequired()->end()
                            ->scalarNode('cache')->isRequired()->end()
                            ->integerNode('max_image_size')->defaultNull()->end()
                            ->variableNode('defaults')->defaultValue([])->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
