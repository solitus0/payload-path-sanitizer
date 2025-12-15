<?php

declare(strict_types=1);

namespace Solitus0\PayloadSanitizer\Tests;

use PHPUnit\Framework\TestCase;
use Solitus0\PayloadSanitizer\Matcher\KeyMatcher;
use Solitus0\PayloadSanitizer\Matcher\ParentKeyMatcher;
use Solitus0\PayloadSanitizer\Matcher\SanitizerMatcherInterface;
use Solitus0\PayloadSanitizer\Rule;
use Solitus0\PayloadSanitizer\Sanitizer;
use Solitus0\PayloadSanitizer\SanitizerContext;
use Solitus0\PayloadSanitizer\ValueSanitizer\ValueSanitizerInterface;

final class SanitizerTest extends TestCase
{
    public function testItSanitizesMatchingKeysUsingRuleArray(): void
    {
        $data = [
            'user' => [
                'credentials' => [
                    'password' => 'secret',
                    'hint' => 'pet name',
                ],
            ],
        ];

        $valueSanitizer = $this->createMock(ValueSanitizerInterface::class);
        $valueSanitizer
            ->expects(self::once())
            ->method('sanitize')
            ->with('secret')
            ->willReturn('MASKED')
        ;

        $rule = new Rule(new KeyMatcher('password'), $valueSanitizer);

        $context = new SanitizerContext($rule);
        $sanitized = (new Sanitizer())->sanitize($data, $context);

        self::assertSame('MASKED', $sanitized['user']['credentials']['password']);
        self::assertSame('pet name', $sanitized['user']['credentials']['hint']);
    }

    public function testItAcceptsSanitizerContextsAndSanitizesAllMatchingLeaves(): void
    {
        $data = [
            'user' => [
                'credentials' => [
                    'token' => 'sensitive-token',
                    'password' => 'another-secret',
                ],
                'profile' => [
                    'email' => 'user@example.com',
                ],
            ],
        ];

        $valueSanitizer = $this->createMock(ValueSanitizerInterface::class);
        $valueSanitizer
            ->expects(self::exactly(2))
            ->method('sanitize')
            ->willReturn('MASKED')
        ;

        $sanitizerContext = new SanitizerContext(
            new Rule(new ParentKeyMatcher('credentials'), $valueSanitizer)
        );

        $sanitized = (new Sanitizer())->sanitize($data, $sanitizerContext);

        self::assertSame('MASKED', $sanitized['user']['credentials']['token']);
        self::assertSame('MASKED', $sanitized['user']['credentials']['password']);
        self::assertSame('user@example.com', $sanitized['user']['profile']['email']);
    }

    public function testItDoesNotRunSubsequentRulesOnceValueWasReplaced(): void
    {
        $data = [
            'password' => 'secret',
        ];

        $firstValueSanitizer = $this->createMock(ValueSanitizerInterface::class);
        $firstValueSanitizer
            ->expects(self::once())
            ->method('sanitize')
            ->with('secret')
            ->willReturn('MASKED')
        ;

        $secondMatcher = $this->createMock(SanitizerMatcherInterface::class);
        $secondMatcher->expects(self::never())->method('matches');
        $secondValueSanitizer = $this->createMock(ValueSanitizerInterface::class);
        $secondValueSanitizer->expects(self::never())->method('sanitize');

        $context = new SanitizerContext(
            new Rule(new KeyMatcher('password'), $firstValueSanitizer),
            new Rule($secondMatcher, $secondValueSanitizer)
        );

        $sanitized = (new Sanitizer())->sanitize($data, $context);

        self::assertSame('MASKED', $sanitized['password']);
    }

    public function testItThrowsWhenRuleIsNotInstanceOfRule(): void
    {
        $this->expectException(\TypeError::class);

        /**
         * @phpstan-ignore-next-line We intentionally pass an invalid rule implementation to
         *                              assert the constructor type-check safeguards.
         */
        new SanitizerContext(new \stdClass());
    }

    public function testItSanitizesPasswordThreeLevelsDeep(): void
    {
        $data = [
            'outer' => [
                'middle' => [
                    'inner' => [
                        'password' => 'top-secret',
                        'other' => 'visible',
                    ],
                ],
            ],
        ];

        $valueSanitizer = $this->createMock(ValueSanitizerInterface::class);
        $valueSanitizer
            ->expects(self::once())
            ->method('sanitize')
            ->with('top-secret')
            ->willReturn('MASKED')
        ;

        $context = new SanitizerContext(
            new Rule(new KeyMatcher('password'), $valueSanitizer)
        );

        $sanitized = (new Sanitizer())->sanitize($data, $context);

        self::assertSame('MASKED', $sanitized['outer']['middle']['inner']['password']);
        self::assertSame('visible', $sanitized['outer']['middle']['inner']['other']);
    }
}
