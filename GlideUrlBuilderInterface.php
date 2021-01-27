<?php

namespace Erichard\Bundle\GlideBundle;

interface GlideUrlBuilderInterface
{
    public function buildUrl(string $server, string $path, array $params = []): string;
}
