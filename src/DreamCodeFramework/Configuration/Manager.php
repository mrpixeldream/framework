<?php

declare(strict_types=1);

namespace DreamCodeFramework\Configuration;

use DreamCodeFramework\Configuration\Exceptions\NoLoadersRegisteredException;
use DreamCodeFramework\Configuration\Exceptions\InvalidConfigurationKeyException;

class Manager
{
    /**
     * @var LoaderInterface[]
     */
    private $loaders = [];

    public function addLoader(LoaderInterface $loader): void
    {
        $this->loaders[] = $loader;
    }

    public function boot(): void
    {
    }

    /**
     * @param  string                           $key
     * @return string
     * @throws NoLoadersRegisteredException
     * @throws InvalidConfigurationKeyException
     */
    public function get(string $key): string
    {
        if (count($this->loaders) === 0) {
            throw new NoLoadersRegisteredException();
        }

        $value = null;
        foreach ($this->loaders as $loader) {
            $value = $loader->loadKey($key);

            if ($value !== null) {
                break;
            }
        }

        if ($value === null) {
            throw new InvalidConfigurationKeyException($key);
        }

        return $value;
    }
}
