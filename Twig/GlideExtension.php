<?php

namespace Erichard\GlideBundle\Twig;

use Erichard\GlideBundle\GlideUrlBuilder;

class GlideExtension extends \Twig_Extension
{
    protected $glideUrlBuilder;

    public function __construct(GlideUrlBuilder $glideUrlBuilder)
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
