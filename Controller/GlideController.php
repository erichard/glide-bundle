<?php

namespace Erichard\GlideBundle\Controller;

use Erichard\GlideBundle\SymfonyResponseFactory;
use League\Glide\Filesystem\FileNotFoundException;
use League\Glide\ServerFactory;
use League\Glide\Signatures\SignatureException;
use League\Glide\Signatures\SignatureFactory;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GlideController extends Controller
{
    /**
     * @param Request $request
     * @param string  $server
     * @param string  $path
     * @param string  $_format
     *
     * @return Response
     */
    public function resizeAction(Request $request, $server, $path, $_format)
    {
        $servers = $this->getParameter('erichard_glide.servers');

        if (!isset($servers[$server])) {
            throw $this->createAccessDeniedException('Unkown glide server');
        }

        $signatureKey = $this->getParameter('erichard_glide.sign_key');

        if (null !== $signatureKey) {
            try {
                SignatureFactory::create($signatureKey)
                    ->validateRequest(urldecode($request->getPathInfo()), $request->query->all())
                ;
            } catch (SignatureException $e) {
                throw $this->createAccessDeniedException('Invalid image signature');
            }
        }

        $serverConfig = $servers[$server];

        $server = ServerFactory::create([
            'source' => $this->get($serverConfig['source']),
            'cache' => $this->get($serverConfig['cache']),
            'response' => new SymfonyResponseFactory($request),
            'presets' => $this->getParameter('erichard_glide.presets'),
            'max_image_size' => isset($serverConfig['max_image_size']) ? $serverConfig['max_image_size'] : null,
        ]);

        try {
            return $server->getImageResponse("{$path}.{$_format}", $request->query->all());
        } catch (FileNotFoundException $exception) {
            throw $this->createNotFoundException();
        }
    }
}
