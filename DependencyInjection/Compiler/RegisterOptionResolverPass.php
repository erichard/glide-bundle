<?php

namespace Erichard\GlideBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class RegisterOptionResolverPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('erichard_glide.options_resolver')) {
            return;
        }

        $optionsResolver = $container->findDefinition('erichard_glide.options_resolver');

        foreach ($container->findTaggedServiceIds('erichard_glide.options_resolver') as $id => $attributes) {
            $priority = isset($attributes[0]['priority']) ? (int) $attributes[0]['priority'] : 0;
            $optionsResolver->addMethodCall('addResolver', [new Reference($id), $priority]);
        }
    }
}
