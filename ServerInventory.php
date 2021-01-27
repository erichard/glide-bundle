<?php

namespace Erichard\Bundle\GlideBundle;

use League\Glide\Server;

class ServerInventory implements ServerInventoryInterface
{
    /** @var array */
    private $servers = [];

    public function add(string $id, Server $server)
    {
        $this->servers[$id] = $server;
    }

    public function has(string $id): bool
    {
        return isset($this->servers[$id]);
    }

    public function get(string $id): ?Server
    {
        return $this->servers[$id] ?? null;
    }
}
