<?php

declare(strict_types=1);

namespace DreamCodeFramework\Configuration;

interface LoaderInterface
{
    public function loadKey(string $key): ?string;
}
