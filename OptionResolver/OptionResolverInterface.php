<?php

namespace Erichard\Bundle\GlideBundle\OptionResolver;

interface OptionResolverInterface
{
    public function resolveOptions(array $options, string $server = null): array;
}
