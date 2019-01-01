<?php

namespace DreamCodeFramework\Configuration\Exceptions;

use Exception;

class NoLoadersRegisteredException extends Exception
{
    public function __construct()
    {
        parent::__construct('No configuration loaders were registered when trying to load a configuration key.');
    }
}
