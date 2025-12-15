<?php

declare(strict_types=1);

namespace Solitus0\PayloadSanitizer\Tests;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Solitus0\PayloadSanitizer\Matcher\SanitizerMatcherInterface;
use Solitus0\PayloadSanitizer\Rule;
use Solitus0\PayloadSanitizer\ValueSanitizer\ValueSanitizerInterface;

final class RuleTest extends TestCase
{
    private SanitizerMatcherInterface&MockObject $matcher;

    private ValueSanitizerInterface&MockObject $valueSanitizer;

    protected function setUp(): void
    {
        $this->matcher = $this->createMock(SanitizerMatcherInterface::class);
        $this->valueSanitizer = $this->createMock(ValueSanitizerInterface::class);
    }

    public function testExposesMatcherAndValueSanitizer(): void
    {
        $rule = new Rule($this->matcher, $this->valueSanitizer);

        self::assertSame($this->matcher, $rule->matcher());
        self::assertSame($this->valueSanitizer, $rule->valueSanitizer());
    }
}
