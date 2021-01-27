<?php

namespace Erichard\Bundle\GlideBundle;

use League\Glide\Server;

interface ServerInventoryInterface
{
    public function add(string $id, Server $server);

    public function has(string $id): bool;

    public function get(string $id): ?Server;
}
