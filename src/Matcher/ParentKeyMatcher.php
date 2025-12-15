<?php

declare(strict_types=1);

namespace Solitus0\PayloadSanitizer\Matcher;

readonly class ParentKeyMatcher implements SanitizerMatcherInterface
{
    public function __construct(
        private string $parentKey
    ) {
    }

    public function matches($key, array $path, $value = null): bool
    {
        return (count($path) >= 2) && ($path[count($path) - 2] === $this->parentKey);
    }
}
