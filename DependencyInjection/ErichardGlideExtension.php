<?php

namespace Erichard\GlideBundle\DependencyInjection;

use Erichard\GlideBundle\ServerRepository;
use Erichard\GlideBundle\SymfonyResponseFactory;
use League\Glide\Server;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;

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

        $container
            ->setDefinition($id, new ChildDefinition(Server::class))
            ->replaceArgument(0, [
                'source' => new Reference($source),
                'cache' => new Reference($cache),
                'response' => new Reference(SymfonyResponseFactory::class),
                'defaults' => $defaults,
                'presets' => $presets,
                'max_image_size' => $maxImageSize,
           ])
        ;

        $container
            ->getDefinition(ServerRepository::class)
            ->addMethodCall('addServer', [$id, new Reference($id)])
        ;
    }
}
