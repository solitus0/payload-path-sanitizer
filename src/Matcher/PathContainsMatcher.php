<?php

declare(strict_types=1);

namespace Solitus0\PayloadSanitizer\Matcher;

class PathContainsMatcher implements SanitizerMatcherInterface
{
    public function __construct(
        private array $keys
    ) {
    }

    public function matches($key, array $path, $value = null): bool
    {
        return count(array_intersect($this->keys, $path)) === count($this->keys);
    }
}
