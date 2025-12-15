<?php

declare(strict_types=1);

namespace Solitus0\PayloadSanitizer\Tests\Matcher;

use PHPUnit\Framework\TestCase;
use Solitus0\PayloadSanitizer\Matcher\ParentKeyMatcher;

final class ParentKeyMatcherTest extends TestCase
{
    public function testMatchesWhenParentKeyIsPresent(): void
    {
        $matcher = new ParentKeyMatcher('credentials');
        $path = ['user', 'credentials', 'password'];

        self::assertTrue($matcher->matches('password', $path, 'value'));
    }

    public function testDoesNotMatchWhenParentKeyIsMissing(): void
    {
        $matcher = new ParentKeyMatcher('credentials');
        $path = ['user', 'profile', 'password'];

        self::assertFalse($matcher->matches('password', $path, 'value'));
    }

    public function testDoesNotMatchWhenPathTooShallow(): void
    {
        $matcher = new ParentKeyMatcher('credentials');
        $path = ['password'];

        self::assertFalse($matcher->matches('password', $path, 'value'));
    }
}
