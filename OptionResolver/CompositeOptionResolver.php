<?php

namespace Erichard\Bundle\GlideBundle\OptionResolver;

class CompositeOptionResolver implements OptionResolverInterface
{
    /** @var array|OptionResolverInterface[] */
    private $resolvers = [];

    public function __construct(iterable $resolvers)
    {
        $this->resolvers = $resolvers;
    }

    public function resolveOptions(array $options, string $server = null): array
    {
        foreach ($this->resolvers as $resolver) {
            $options = $resolver->resolveOptions($options, $server);
        }

        return $options;
    }
}
