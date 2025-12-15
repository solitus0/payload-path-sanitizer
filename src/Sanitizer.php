<?php

declare(strict_types=1);

namespace Solitus0\PayloadSanitizer;

final class Sanitizer
{
    public function sanitize(array $data, SanitizerContext $sanitizerContext): array
    {
        $recursiveArrayIterator = new \RecursiveArrayIterator($data);
        $recursiveIteratorIterator = new \RecursiveIteratorIterator(
            $recursiveArrayIterator,
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($recursiveIteratorIterator as $key => $value) {
            $depth = $recursiveIteratorIterator->getDepth();
            $path = [];
            for ($d = 0; $d <= $depth; ++$d) {
                $path[] = $recursiveIteratorIterator->getSubIterator($d)->key();
            }

            foreach ($sanitizerContext as $rule) {
                $matcher = $rule->matcher();
                if (!$matcher->matches($key, $path, $value)) {
                    continue;
                }

                $sanitizedValue = $rule->valueSanitizer()->sanitize($value);

                if ($this->replaceLeaf($data, $path, $sanitizedValue)) {
                    continue 2;
                }
            }
        }

        return $data;
    }

    private function replaceLeaf(array &$payload, array $path, mixed $value): bool
    {
        $cursor = &$payload;
        $lastIndex = array_key_last($path);

        foreach ($path as $depth => $segment) {
            if (!is_array($cursor) || !array_key_exists($segment, $cursor)) {
                return false;
            }

            if ($depth === $lastIndex) {
                $cursor[$segment] = $value;

                return true;
            }

            $cursor = &$cursor[$segment];
        }

        return false;
    }
}
