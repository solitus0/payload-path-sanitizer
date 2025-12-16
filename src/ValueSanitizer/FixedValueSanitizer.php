<?php

declare(strict_types=1);

namespace Solitus0\PayloadSanitizer\ValueSanitizer;

final class FixedValueSanitizer implements ValueSanitizerInterface
{
    public function __construct(
        private readonly string $replacement = '[SANITIZED]'
    ) {
    }

    public function sanitize($value): string
    {
        return $this->replacement;
    }
}
