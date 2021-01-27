<?php

namespace Erichard\Bundle\GlideBundle;

use League\Glide\Signatures\SignatureException;
use League\Glide\Signatures\SignatureFactory;
use Symfony\Component\HttpFoundation\Request;

class SignatureChecker implements SignatureCheckerInterface
{
    /** @var string|null */
    private $signatureKey;

    public function __construct(?string $signatureKey)
    {
        $this->signatureKey = $signatureKey;
    }

    public function check(Request $request): bool
    {
        if (!$this->signatureKey) {
            return true;
        }

        try {
            SignatureFactory::create($this->signatureKey)
                ->validateRequest(urldecode($request->getPathInfo()), $request->query->all())
            ;
        } catch (SignatureException $e) {
            return false;
        }

        return true;
    }
}
