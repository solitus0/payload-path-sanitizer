<?php

declare(strict_types=1);

namespace Solitus0\PayloadSanitizer\Tests\Matcher;

use PHPUnit\Framework\TestCase;
use Solitus0\PayloadSanitizer\Matcher\PathContainsMatcher;

final class PathContainsMatcherTest extends TestCase
{
    public function testMatchesWhenEntireSequenceIsContained(): void
    {
        $matcher = new PathContainsMatcher(['user', 'credentials']);
        $path = ['user', 'credentials', 'password'];

        self::assertTrue($matcher->matches('password', $path, 'value'));
    }

    public function testDoesNotMatchWhenKeysMissing(): void
    {
        $matcher = new PathContainsMatcher(['user', 'credentials']);
        $path = ['user', 'profile', 'password'];

        self::assertFalse($matcher->matches('password', $path, 'value'));
    }
}
