<?php

namespace Erichard\Bundle\GlideBundle\Twig;

use Erichard\Bundle\GlideBundle\GlideUrlBuilderInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class GlideExtension extends AbstractExtension
{
    /** @var GlideUrlBuilderInterface */
    protected $glideUrlBuilder;

    public function __construct(GlideUrlBuilderInterface $glideUrlBuilder)
    {
        $this->glideUrlBuilder = $glideUrlBuilder;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('glideUrl', [$this, 'glideUrl']),
        ];
    }

    public function glideUrl($server, $path, array $params = [])
    {
        return $this->glideUrlBuilder->buildUrl($server, $path, $params);
    }
}
