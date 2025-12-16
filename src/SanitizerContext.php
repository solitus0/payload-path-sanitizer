<?php

declare(strict_types=1);

namespace Solitus0\PayloadSanitizer;

use Solitus0\PayloadSanitizer\Matcher\SanitizerMatcherInterface;
use Solitus0\PayloadSanitizer\ValueSanitizer\ValueSanitizerInterface;

final class SanitizerContext implements \IteratorAggregate
{
    /** @var Rule[] */
    private array $rules = [];

    /**
     * @param Rule|Rule[] ...$rules
     */
    public function __construct(Rule|array ...$rules)
    {
        $this->appendRules($rules);
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

    /**
     * @param array<int, Rule|Rule[]> $rules
     */
    private function appendRules(array $rules): void
    {
        foreach ($rules as $rule) {
            if (\is_array($rule)) {
                $this->appendRules($rule);

                continue;
            }

            $this->add($rule);
        }
    }
}
