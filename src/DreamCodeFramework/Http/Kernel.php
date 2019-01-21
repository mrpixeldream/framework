<?php

declare(strict_types=1);

namespace DreamCodeFramework\Http;

use Symfony\Component\HttpKernel\HttpKernel;

class Kernel extends HttpKernel
{
    private $request;

    private $controllerResolver;
    private $argumentResolver;

    public static function boot(): self
    {
    }
}
