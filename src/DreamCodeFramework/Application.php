<?php

declare(strict_types=1);

namespace DreamCodeFramework;

use DreamCodeFramework\Container\Container;
use DreamCodeFramework\Http\Kernel;
use Symfony\Component\HttpKernel\HttpKernel;

/**
 * Class Application
 * @package DreamCodeFramework
 */
class Application
{
    private $container;
    private $kernel;
    
    public function __construct()
    {
        /*
         * First, bootstrap the IoC container for dependency injection to be able to resolve everything later on.
         */
        $this->container = new Container();
        $this->container->boot();
        
        /*
         * Then we need to bootstrap the Http kernel to handle the request.
         */
        $this->kernel = new Kernel();
        $this->kernel->boot();
    }
}
