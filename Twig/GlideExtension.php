<?php

namespace Erichard\Bundle\GlideBundle\Twig;

use Erichard\Bundle\GlideBundle\GlideUrlBuilderInterface;

class GlideExtension extends \Twig_Extension
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
            new \Twig_SimpleFunction('glideUrl', [$this, 'glideUrl']),
        ];
    }

    public function glideUrl($server, $path, array $params = [])
    {
        return $this->glideUrlBuilder->buildUrl($server, $path, $params);
    }
}
