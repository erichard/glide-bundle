<?php

namespace Erichard\GlideBundle\Controller;

use Erichard\GlideBundle\ServerRepository;
use League\Glide\Filesystem\FileNotFoundException;
use League\Glide\Signatures\SignatureException;
use League\Glide\Signatures\SignatureFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GlideController extends AbstractController
{
    private $serverRepository;

    public function __construct(ServerRepository $serverRepository)
    {
        $this->serverRepository = $serverRepository;
    }

    /**
     * @param string  $server
     * @param string  $path
     * @param string  $_format
     *
     * @return Response
     */
    public function resizeAction(Request $request, $server, $path, $_format)
    {
        $serverId = "erichard_glide.${server}_server";

        if (!$this->serverRepository->hasServer($serverId)) {
            throw $this->createNotFoundException();
        }

        $signatureKey = $this->getParameter('erichard_glide.sign_key');

        if (null !== $signatureKey) {
            try {
                SignatureFactory::create($signatureKey)
                    ->validateRequest(urldecode($request->getPathInfo()), $request->query->all())
                ;
            } catch (SignatureException $e) {
                throw $this->createNotFoundException();
            }
        }

        $server = $this->serverRepository->getServer($serverId);

        try {
            return $server->getImageResponse("{$path}.{$_format}", $request->query->all());
        } catch (FileNotFoundException $exception) {
            throw $this->createNotFoundException();
        }
    }
}
