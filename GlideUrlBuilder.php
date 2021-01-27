<?php

namespace Erichard\Bundle\GlideBundle;

use League\Glide\Urls\UrlBuilderFactory;

class GlideUrlBuilder implements GlideUrlBuilderInterface
{
    /** @var string */
    protected $signkey;

    public function __construct(string $signkey = null)
    {
        $this->signkey = $signkey;
    }

    public function buildUrl(string $server, string $path, array $params = []): string
    {
        $urlBuilder = UrlBuilderFactory::create("/$server/", $this->signkey);

        return $urlBuilder->getUrl($path, $params);
    }
}
