<?php

declare(strict_types=1);

namespace Solitus0\PayloadSanitizer\Tests\Matcher;

use PHPUnit\Framework\TestCase;
use Solitus0\PayloadSanitizer\Matcher\KeyMatcher;

final class KeyMatcherTest extends TestCase
{
    public function testMatchesReturnsTrueWhenKeysAreEqual(): void
    {
        $matcher = new KeyMatcher('secret');

        self::assertTrue($matcher->matches('secret', ['user', 'secret'], 'value'));
    }

    public function testMatchesReturnsFalseWhenKeysDiffer(): void
    {
        $matcher = new KeyMatcher('secret');

        self::assertFalse($matcher->matches('email', ['user', 'email'], 'value'));
    }
}
