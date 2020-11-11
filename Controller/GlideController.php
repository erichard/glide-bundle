<?php

namespace Erichard\GlideBundle\Controller;

use League\Glide\Filesystem\FileNotFoundException;
use League\Glide\Signatures\SignatureException;
use League\Glide\Signatures\SignatureFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Psr\Container\ContainerInterface;

class GlideController extends AbstractController
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
        $serverId = "erichard_glide.${server}_server";

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

        $server = $this->get($serverId);

        try {
            return $server->getImageResponse("{$path}.{$_format}", $request->query->all());
        } catch (FileNotFoundException $exception) {
            throw $this->createNotFoundException();
        }
    }
    
    public static function getSubscribedServices()
    {
        return array_merge(parent::getSubscribedServices(), [
            'erichard_glide.image_server' => '?'.League\Glide\Server::class,
        ]);
    }
    
}
