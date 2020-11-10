<?php

namespace Erichard\GlideBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Extension\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @see http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class ErichardGlideExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $container->setParameter('erichard_glide.sign_key', $config['sign_key']);

        foreach ($config['servers'] as $name => $server) {
            $this->createServer($name, $server['source'], $server['cache'], $container, $server['defaults'], $config['presets'], $server['max_image_size']);
        }
    }

    public function createServer($name, $source, $cache, ContainerBuilder $container, array $defaults = [], array $presets = [], $maxImageSize = null)
    {
        $id = sprintf('erichard_glide.%s_server', $name);
        
        $definition = class_exists('\Symfony\Component\DependencyInjection\ChildDefinition')
            ? new ChildDefinition('erichard_glide.server')
            : new DefinitionDecorator('erichard_glide.server');

        $container
            ->setDefinition($id, $definition)
            ->replaceArgument(0, [
                'source' => new Reference($source),
                'cache' => new Reference($cache),
                'response' => new Reference('erichard_glide.symfony_response_factory'),
                'defaults' => $defaults,
                'presets' => $presets,
                'max_image_size' => $maxImageSize,
           ])
            ->setPublic(true)
        ;
    }
}
