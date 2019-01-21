<?php

declare(strict_types=1);

namespace DreamCodeFramework\Configuration\Concrete;

use DreamCodeFramework\Configuration\Exceptions\InvalidConfigurationKeyException;
use DreamCodeFramework\Configuration\LoaderInterface;
use DreamCodeFramework\Utility\Str;

class FileLoader implements LoaderInterface
{
    const SUFFIX = '.php';

    private $path;
    private $files;

    private $keys = [];

    public function __construct(string $path)
    {
        $this->path = $path;

        $this->files = collect(scandir($this->path))
            ->filter(function ($value) {
                return Str::endsWith($value, static::SUFFIX);
            })
            ->transform(function ($value) {
                return Str::replaceLast(static::SUFFIX, '', $value);
            });
    }

    /**
     * This method returns an array of all registered files.
     * The registered configuration files are internally handled as a Collection, but returned as array to reduce coupling for the user of this framework.
     *
     * @return array An array of all registered files
     */
    public function getRegisteredFiles(): array
    {
        return $this->files->toArray();
    }

    /**
     * @param string $key
     *
     * @throws InvalidConfigurationKeyException
     *
     * @return string|null
     */
    public function loadKey(string $key): ?string
    {
        $filePrefix = Str::pop($key, '.');

        if (!array_key_exists($filePrefix, $this->keys)) {
            // Not loaded yet. We need to look it up in the matching file.
            if ($this->isFileRegistered($filePrefix)) {
                $this->keys[$filePrefix] = require $this->path.DIRECTORY_SEPARATOR.$filePrefix.static::SUFFIX;
            } else {
                throw new InvalidConfigurationKeyException('No file was registered to match the first part of the given key: '.$filePrefix);
            }
        }

        if (Str::contains($key, '.')) {
            return $this->getRecursive($key, $this->keys[$filePrefix]);
        }

        if (!isset($this->keys[$filePrefix][$key])) {
            throw new InvalidConfigurationKeyException('The given key '.$key.' was not found in file '.$filePrefix);
        }

        return $this->keys[$filePrefix][$key];
    }

    /**
     * @param string $key
     * @param array  $subArray
     *
     * @throws InvalidConfigurationKeyException
     *
     * @return string|null
     */
    private function getRecursive(string $key, array $subArray): ?string
    {
        $prefix = Str::pop($key, '.');
        if (Str::contains($key, '.')) {
            if (!is_array($subArray[$prefix])) {
                throw new InvalidConfigurationKeyException('Tried to resolve key recursively but found literal value instead of array before key parsing ended. Stopped at '.$prefix);
            }

            return $this->getRecursive($key, $subArray[$prefix]);
        }

        return $subArray[$prefix][$key];
    }

    private function isFileRegistered(string $file)
    {
        return $this->files->contains($file) || $this->files->contains($file.static::SUFFIX);
    }
}
