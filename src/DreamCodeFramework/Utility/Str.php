<?php

declare(strict_types=1);

namespace DreamCodeFramework\Utility;

class Str extends \Illuminate\Support\Str
{
    /**
     * Splits the string using $delimiter and pops the first element off of it.
     * @param  string      $subject
     * @param  string      $delimiter
     * @return string|null
     */
    public static function pop(string &$subject, string $delimiter = ' '): ?string
    {
        if (! static::contains($subject, $delimiter)) {
            return null;
        }

        $parts = collect(explode($delimiter, $subject));
        $prefix = $parts->shift();
        $subject = $parts->implode($delimiter);

        return $prefix;
    }
}
