<?php

namespace Erichard\GlideBundle\OptionResolver;

use Erichard\GlideBundle\OptionResolverInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class ClientHintsResolver implements OptionResolverInterface
{
    private $requestStack;
    private $matchDPR;

    public function __construct(RequestStack $requestStack, bool $matchDPR)
    {
        $this->requestStack = $requestStack;
        $this->matchDPR = $matchDPR;
    }

    public function resolveOptions(array $options, string $server = null)
    {
        $request = $this->requestStack->getCurrentRequest();

        // Pixel density
        $dpr = $request->headers->get('dpr', 1);
        if ($this->matchDPR && $dpr > 1) {
            $options['dpr'] = $dpr;
        }

        // Do not generate images larger than the viewport Width
        $maxWidth = $request->headers->get('viewport-width', 0);
        if ($maxWidth > 0 && (!isset($options['w']) || $options['w'] > $maxWidth)) {
            $options['w'] = $maxWidth;
        }

        return $options;
    }
}
