<?php

namespace Erichard\GlideBundle;

use League\Glide\Server;

class ServerRepository
{
    /** @var array */
    private $servers = [];

    public function addServer(string $id, Server $server): self
    {
        $this->servers[$id] = $server;

        return $this;
    }

    public function getServer(string $id): Server
    {
        return $this->servers[$id] ?? null;
    }

    public function hasServer(string $id): bool
    {
        return null !== $this->servers[$id];
    }
}
