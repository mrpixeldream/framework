<?php

declare(strict_types=1);

namespace DreamCodeFramework\Configuration;

use DreamCodeFramework\Configuration\Exceptions\InvalidConfigurationKeyException;
use DreamCodeFramework\Configuration\Exceptions\NoLoadersRegisteredException;

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
     * @param string $key
     *
     * @throws NoLoadersRegisteredException
     * @throws InvalidConfigurationKeyException
     *
     * @return string
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
