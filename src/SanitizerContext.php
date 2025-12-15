<?php

declare(strict_types=1);

namespace Solitus0\PayloadSanitizer;

use Solitus0\PayloadSanitizer\Matcher\SanitizerMatcherInterface;
use Solitus0\PayloadSanitizer\ValueSanitizer\ValueSanitizerInterface;

final class SanitizerContext implements \IteratorAggregate
{
    /** @var Rule[] */
    private array $rules = [];

    public function __construct(Rule ...$rules)
    {
        foreach ($rules as $rule) {
            $this->add($rule);
        }
    }

    public function addRule(SanitizerMatcherInterface $matcher, ValueSanitizerInterface $valueSanitizer): self
    {
        return $this->add(new Rule($matcher, $valueSanitizer));
    }

    public function add(Rule $rule): self
    {
        $this->rules[] = $rule;

        return $this;
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->rules);
    }
}
