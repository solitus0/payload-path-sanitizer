<?php

declare(strict_types=1);

namespace Solitus0\PayloadSanitizer\Matcher;

interface SanitizerMatcherInterface
{
    public function matches($key, array $path, $value = null): bool;
}
