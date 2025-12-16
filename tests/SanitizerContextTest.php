<?php

declare(strict_types=1);

namespace Solitus0\PayloadSanitizer\Tests;

use PHPUnit\Framework\TestCase;
use Solitus0\PayloadSanitizer\Matcher\SanitizerMatcherInterface;
use Solitus0\PayloadSanitizer\Rule;
use Solitus0\PayloadSanitizer\SanitizerContext;
use Solitus0\PayloadSanitizer\ValueSanitizer\ValueSanitizerInterface;

final class SanitizerContextTest extends TestCase
{
    public function testIteratesOverInitiallyProvidedRules(): void
    {
        $ruleA = $this->createRule();
        $ruleB = $this->createRule();

        $collection = new SanitizerContext($ruleA, $ruleB);

        self::assertSame([$ruleA, $ruleB], iterator_to_array($collection));
    }

    public function testAddRuleCreatesRuleFromMatcherAndValueSanitizer(): void
    {
        $matcher = $this->createMock(SanitizerMatcherInterface::class);
        $valueSanitizer = $this->createMock(ValueSanitizerInterface::class);
        $collection = new SanitizerContext();

        $result = $collection->addRule($matcher, $valueSanitizer);

        self::assertSame($collection, $result);
        $rules = iterator_to_array($collection);
        self::assertCount(1, $rules);
        self::assertInstanceOf(Rule::class, $rules[0]);
        self::assertSame($matcher, $rules[0]->matcher());
        self::assertSame($valueSanitizer, $rules[0]->valueSanitizer());
    }

    public function testAddAppendsRule(): void
    {
        $ruleA = $this->createRule();
        $ruleB = $this->createRule();
        $collection = new SanitizerContext($ruleA);

        $collection->add($ruleB);

        self::assertSame([$ruleA, $ruleB], iterator_to_array($collection));
    }

    public function testConstructorAcceptsPlainRuleArray(): void
    {
        $ruleA = $this->createRule();
        $ruleB = $this->createRule();

        $collection = new SanitizerContext([$ruleA, $ruleB]);

        self::assertSame([$ruleA, $ruleB], iterator_to_array($collection));
    }

    private function createRule(): Rule
    {
        $matcher = $this->createMock(SanitizerMatcherInterface::class);
        $valueSanitizer = $this->createMock(ValueSanitizerInterface::class);

        return new Rule($matcher, $valueSanitizer);
    }
}
