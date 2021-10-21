<?php

declare(strict_types=1);

namespace Erichard\Bundle\GlideBundle\OptionResolver;

use Symfony\Component\HttpFoundation\RequestStack;

class AcceptAvifResolver implements OptionResolverInterface
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function resolveOptions(array $options, string $server = null): array
    {
        $request = $this->requestStack->getCurrentRequest();

        if (\in_array('image/avif', $request->getAcceptableContentTypes())) {
            $options['fm'] = 'avif';
        }

        return $options;
    }
}
