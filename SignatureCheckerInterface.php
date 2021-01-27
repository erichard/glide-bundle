<?php

namespace Erichard\Bundle\GlideBundle;

use Symfony\Component\HttpFoundation\Request;

interface SignatureCheckerInterface
{
    public function check(Request $request): bool;
}
