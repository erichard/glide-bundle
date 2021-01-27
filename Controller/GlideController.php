<?php

namespace Erichard\Bundle\GlideBundle\Controller;

use Erichard\Bundle\GlideBundle\OptionResolver\OptionResolverInterface;
use Erichard\Bundle\GlideBundle\ServerInventoryInterface;
use Erichard\Bundle\GlideBundle\SignatureCheckerInterface;
use League\Glide\Filesystem\FileNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GlideController
{
    /**
     * @var array
     */
    const PARAMS = [
        'or', 'flip', 'crop', 'w', 'h', 'fit', 'dpr', 'bri',
        'con', 'gam', 'sharp', 'blur', 'pixel', 'filt',
        'mark', 'markw', 'markh', 'markx', 'marky', 'markpad', 'markpos', 'markalpha',
        'bg', 'border', 'q', 'fm', 'p',
    ];

    /** @var SignatureCheckerInterface */
    private $signatureChecker;

    /** @var OptionResolverInterface */
    private $optionResolver;

    /** @var ServerInventoryInterface */
    private $serverInventory;

    public function __construct(SignatureCheckerInterface $signatureChecker, OptionResolverInterface $optionResolver, ServerInventoryInterface $serverInventory)
    {
        $this->signatureChecker = $signatureChecker;
        $this->optionResolver = $optionResolver;
        $this->serverInventory = $serverInventory;
    }

    public function resize(Request $request, string $server, string $path, string $_format): Response
    {
        if (!$this->serverInventory->has($server) || !$this->signatureChecker->check($request)) {
            throw new NotFoundHttpException('Invalid server or signature');
        }

        $options = $this->getOptionsForServer($server, $request);
        $glideServer = $this->serverInventory->get($server);

        try {
            $response = $glideServer->getImageResponse("{$path}.{$_format}", $options);
        } catch (FileNotFoundException $exception) {
            throw new NotFoundHttpException('Source file was not found');
        }

        if ($this->isWebPFormat($options)) {
            $this->addVaryHeaderForWebP($response);
        }

        return $response;
    }

    private function getOptionsForServer(string $server, Request $request): array
    {
        $glideServer = $this->serverInventory->get($server);

        $baseOptions = $glideServer->getAllParams($request->query->all());

        $options = $this
            ->optionResolver
            ->resolveOptions($baseOptions, $server);

        $options = array_filter($options);

        foreach ($options as $option => $value) {
            if (!in_array($option, self::PARAMS)) {
                unset($options[$option]);
            }
        }

        return $options;
    }

    private function isWebPFormat(array $options): bool
    {
        return isset($options['fm']) && 'webp' === $options['fm'];
    }

    private function addVaryHeaderForWebP(Response $response): void
    {
        $response->headers->set('Content-Type', 'image/webp');
        $response->setVary(['Accept']);
    }
}
