<?php

namespace Erichard\GlideBundle;

use Erichard\GlideBundle\DependencyInjection\Compiler\RegisterOptionResolverPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ErichardGlideBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new RegisterOptionResolverPass());
    }
}
