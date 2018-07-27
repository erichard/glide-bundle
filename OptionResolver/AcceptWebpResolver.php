<?php

namespace Erichard\GlideBundle\OptionResolver;

use Erichard\GlideBundle\OptionResolverInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class AcceptWebpResolver implements OptionResolverInterface
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function resolveOptions(array $options, string $server = null)
    {
        $request = $this->requestStack->getCurrentRequest();

        if (!isset($options['fm']) && in_array('image/webp', $request->getAcceptableContentTypes())) {
            $options['fm'] = 'webp';
        }

        return $options;
    }
}
