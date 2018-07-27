<?php

namespace Erichard\GlideBundle;

interface OptionResolverInterface
{
    public function resolveOptions(array $options, string $server = null);
}
