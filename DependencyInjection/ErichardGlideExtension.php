<?php

declare(strict_types=1);

namespace Erichard\Bundle\GlideBundle\DependencyInjection;

use Erichard\Bundle\GlideBundle\OptionResolver\OptionResolverInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

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

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $container
            ->getDefinition('erichard_glide.url_builder')
            ->replaceArgument(0, $config['sign_key']);

        $container
            ->getDefinition('erichard_glide.signature_checker')
            ->replaceArgument(0, $config['sign_key']);

        $container->setParameter('erichard_glide.sign_key', $config['sign_key']);

        foreach ($config['servers'] as $name => $server) {
            $this->createServer($name, $server['source'], $server['cache'], $container, $server['defaults'], $config['presets'], $server['max_image_size'], $server['driver']);
        }

        $container
            ->registerForAutoconfiguration(OptionResolverInterface::class)
            ->addTag('erichard_glide.options_resolver')
        ;

        if (!$config['accept_webp']['enabled']) {
            $container->removeDefinition('erichard_glide.webp_resolver');
        }

        if (!$config['accept_avif']['enabled']) {
            $container->removeDefinition('erichard_glide.avif_resolver');
        }
    }

    private function createServer($name, $source, $cache, ContainerBuilder $container, array $defaults = [], array $presets = [], $maxImageSize = null, string $driver = null)
    {
        $id = sprintf('erichard_glide.%s_server', $name);

        $container
            ->setDefinition($id, new ChildDefinition('erichard_glide.server'))
            ->replaceArgument(0, [
                'source' => new Reference($source),
                'cache' => new Reference($cache),
                'response' => new Reference('erichard_glide.symfony_response_factory'),
                'defaults' => $defaults,
                'presets' => $presets,
                'driver' => $driver,
                'max_image_size' => $maxImageSize,
           ])
            ->setPublic(true)
        ;

        $container
            ->getDefinition('erichard_glide.server_inventory')
            ->addMethodCall('add', [$name, new Reference($id)])
        ;
    }
}
