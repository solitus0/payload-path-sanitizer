<?php

declare(strict_types=1);

namespace Solitus0\PayloadSanitizer\ValueSanitizer;

readonly class SimpleValueSanitizer implements ValueSanitizerInterface
{
    public function sanitize($value): string
    {
        if (is_string($value)) {
            return sprintf('SANITIZED (%s)', strlen($value));
        }

        return sprintf('SANITIZED (%s)', gettype($value));
    }
}
