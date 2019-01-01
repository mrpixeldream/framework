<?php

declare(strict_types=1);

namespace DreamCodeFramework\Configuration\Concrete;

use DreamCodeFramework\Configuration\LoaderInterface;
use Illuminate\Support\Str;

class FileLoader implements LoaderInterface
{
    const SUFFIX = '.php';

    private $path;
    private $files;
    
    private $keys = [];

    function __construct(string $path)
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
     * @return array An array of all registered files
     */
    public function getRegisteredFiles(): array
    {
        return $this->files->toArray();
    }

    function loadKey(string $key): ?string
    {
        $filePrefix = $this->popFilePrefix($key);
        
        if (! array_key_exists($filePrefix, $this->keys)) {
            // Not loaded yet. We need to look it up in the matching file.
            if ($this->isFileRegistered($filePrefix)) {
                $this->keys[$filePrefix] = require $this->path.DIRECTORY_SEPARATOR.$filePrefix.static::SUFFIX;
            }
        }
        
        return $this->keys[$filePrefix][$key];
    }
    
    private function popFilePrefix(string &$key): string 
    {
        if (! Str::contains($key, '.')) {
            return $key;
        }
        
        $parts = collect(explode('.', $key));
        $prefix = $parts->shift();
        $key = $parts->implode('.');
        
        return $prefix;
    }

    private function isFileRegistered(string $file)
    {
        return $this->files->contains($file) || $this->files->contains($file.static::SUFFIX);
    }
}
