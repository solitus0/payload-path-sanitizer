<?php

declare(strict_types=1);

namespace Solitus0\PayloadSanitizer\Tests\ValueSanitizer;

use PHPUnit\Framework\TestCase;
use Solitus0\PayloadSanitizer\ValueSanitizer\SimpleValueSanitizer;

final class SimpleValueSanitizerTest extends TestCase
{
    public function testSanitizeReturnsLengthForStrings(): void
    {
        $sanitizer = new SimpleValueSanitizer();

        self::assertSame('SANITIZED (6)', $sanitizer->sanitize('secret'));
    }

    public function testSanitizeReturnsTypeForNonStrings(): void
    {
        $sanitizer = new SimpleValueSanitizer();

        self::assertSame('SANITIZED (array)', $sanitizer->sanitize(['secret']));
    }
}
