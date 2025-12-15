# Payload Path Sanitizer

`solitus0/payload-path-sanitizer` is a minimal PHP 8.1+ library for redacting or masking values deep inside associative payloads before they leave your system (logging, telemetry, queues, etc.). Define *rules* that pair a matcher (decides **where**) with a value sanitizer (decides **how**), then run the sanitizer to get a safe copy of the original payload.

## Installation

```
composer require solitus0/payload-path-sanitizer
```

## Core Concepts

- **Rule** – pairs a `Matcher` and a `ValueSanitizer`.
- **SanitizerContext** – convenient builder/iterator for rules (you can also pass any `iterable<Rule>`).
- **Sanitizer** – walks the payload depth-first and replaces leaf values whenever a rule's matcher returns `true`.

## Quick Start

```php
use Solitus0\PayloadSanitizer\{Rule, SanitizerContext, Sanitizer};
use Solitus0\PayloadSanitizer\Matcher\KeyMatcher;
use Solitus0\PayloadSanitizer\ValueSanitizer\SimpleValueSanitizer;

$context = new SanitizerContext(
    new Rule(new KeyMatcher('password'), new SimpleValueSanitizer()),
);

$sanitized = (new Sanitizer())->sanitize($payload, $context);
```

`SimpleValueSanitizer` replaces the value with a `SANITIZED (...)` marker, but you will usually create your own value sanitizer (see below).

## Custom Matchers

Implement `Solitus0\PayloadSanitizer\Matcher\SanitizerMatcherInterface` and inspect the current leaf:

```php
final class EndsWithIdMatcher implements SanitizerMatcherInterface
{
    public function matches($key, array $path, $value = null): bool
    {
        return str_ends_with((string) $key, '_id');
    }
}
```

Available built-ins:

- `KeyMatcher` – matches an exact key.
- `ParentKeyMatcher` – matches by parent key (useful for targeting nested structures).
- `PathContainsMatcher` – ensures the traversal path contains a set of keys (order agnostic).

Combine matchers by writing your own composite if needed.

## Custom Value Sanitizers

Implement `Solitus0\PayloadSanitizer\ValueSanitizer\ValueSanitizerInterface` and transform the matched value into whatever should be persisted/logged. Typical strategies include fixed strings, hashes, or length-prefixed tokens.

```php
final class FixedMaskSanitizer implements ValueSanitizerInterface
{
    public function sanitize($value): string
    {
        return '***';
    }
}
```

## Advanced Usage

- **Dynamic rule sets** – since `Sanitizer::sanitize` accepts any `iterable<Rule>`, you can yield rules lazily or build them per request.
- **Immutable pipelines** – rules are evaluated in the order provided; the first matching rule wins for each payload leaf, ensuring deterministic behavior.
- **Binary-safe** – matchers receive the raw value, so you can restrict sanitation to certain types before falling back to generic fallbacks.

## Testing Tips

- Assert on the sanitized payload rather than internal behavior.
- Provide representative payload fixtures (arrays with strings, ints, and nested arrays) to ensure matchers behave as expected.

## License

MIT
