<?php

declare(strict_types=1);

namespace Solitus0\PayloadSanitizer\ValueSanitizer;

interface ValueSanitizerInterface
{
    public function sanitize($value): string;
}
