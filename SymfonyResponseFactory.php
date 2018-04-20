<?php

namespace Erichard\GlideBundle;

use League\Flysystem\FilesystemInterface;
use League\Glide\Responses\ResponseFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SymfonyResponseFactory implements ResponseFactoryInterface
{
    /**
     * Request object to check "is not modified".
     *
     * @var Request|null
     */
    protected $request;

    /**
     * Create SymfonyResponseFactory instance.
     *
     * @param Request|null $request request object to check "is not modified"
     */
    public function __construct(Request $request = null)
    {
        $this->request = $request;
    }

    /**
     * Create the response.
     *
     * @param  FilesystemInterface $cache the cache file system
     * @param  string              $path  the cached file path
     *
     * @return StreamedResponse    the response object
     */
    public function create(FilesystemInterface $cache, $path)
    {
        $stream = $cache->readStream($path);

        $response = new StreamedResponse();
        $response->headers->set('Content-Type', $cache->getMimetype($path));
        $response->headers->set('Content-Length', $cache->getSize($path));
        $response->setPublic();
        $response->setMaxAge(31536000);
        $response->setSharedMaxAge(31536000);
        $response->setExpires(date_create()->modify('+1 years'));

        if ($this->request) {
            $response->setLastModified(date_create()->setTimestamp($cache->getTimestamp($path)));
            $response->isNotModified($this->request);
        }

        $response->setCallback(function () use ($stream) {
            if (0 !== ftell($stream)) {
                rewind($stream);
            }
            fpassthru($stream);
            fclose($stream);
        });

        return $response;
    }
}
