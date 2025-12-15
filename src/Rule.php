<?php

declare(strict_types=1);

namespace Solitus0\PayloadSanitizer;

use Solitus0\PayloadSanitizer\Matcher\SanitizerMatcherInterface;
use Solitus0\PayloadSanitizer\ValueSanitizer\ValueSanitizerInterface;

final class Rule
{
    public function __construct(
        private readonly SanitizerMatcherInterface $matcher,
        private readonly ValueSanitizerInterface $valueSanitizer,
    ) {
    }

    public function matcher(): SanitizerMatcherInterface
    {
        return $this->matcher;
    }

    public function valueSanitizer(): ValueSanitizerInterface
    {
        return $this->valueSanitizer;
    }
}
