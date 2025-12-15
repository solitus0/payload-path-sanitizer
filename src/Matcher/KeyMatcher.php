<?php

declare(strict_types=1);

namespace Solitus0\PayloadSanitizer\Matcher;

readonly class KeyMatcher implements SanitizerMatcherInterface
{
    public function __construct(
        private string $key
    ) {
    }

    public function matches($key, array $path, $value = null): bool
    {
        return $key === $this->key;
    }
}
