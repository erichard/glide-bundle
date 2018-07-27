<?php

namespace Erichard\GlideBundle;

use Zend\Stdlib\PriorityQueue;

class CompositeOptionResolver implements OptionResolverInterface
{
    /**
     * @var PriorityQueue|OptionResolverInterface[]
     */
    private $resolvers;

    public function __construct()
    {
        $this->resolvers = new PriorityQueue();
    }

    public function addResolver(OptionResolverInterface $optionResolver, int $priority = 0)
    {
        $this->resolvers->insert($optionResolver, $priority);
    }

    public function resolveOptions(array $options, string $server = null)
    {
        foreach ($this->resolvers as $resolver) {
            $options = $resolver->resolveOptions($options, $server);
        }

        return $options;
    }
}
