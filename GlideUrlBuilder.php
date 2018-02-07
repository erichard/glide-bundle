<?php

namespace Erichard\GlideBundle;

use League\Glide\Urls\UrlBuilderFactory;

class GlideUrlBuilder
{
    protected $signkey;

    public function __construct($signkey)
    {
        $this->signkey = $signkey;
    }

    public function buildUrl($server, $path, array $params = [])
    {
        $urlBuilder = UrlBuilderFactory::create("/$server/", $this->signkey);

        return $urlBuilder->getUrl($path, $params);
    }
}
