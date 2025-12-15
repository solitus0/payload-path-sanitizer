# Repository Guidelines

## Project Structure & Module Organization
- `src/` hosts the production library: `Rule.php`, `SanitizerContext.php`, and `Sanitizer.php` orchestrate traversal, while subfolders such as `Matcher/` and `ValueSanitizer/` contain extensibility points. Keep new matchers or sanitizers inside these namespaces so autoloading from `composer.json` stays PSR-4 compliant.
- Place examples, fixtures, and regression tests in a `tests/` tree that mirrors the `src/` namespace (e.g., `tests/Matcher/KeyMatcherTest.php`). PHPUnit already looks for this layout via `phpunit.xml.dist`.
- Tooling lives at the root: `Makefile` for automation, `ecs.php` for coding standard rules, and `.editorconfig` for shared IDE settings.

## Build, Test, and Development Commands
- `composer install` — install PHP 8.1-compatible dependencies.
- `make ecs` / `make ecs_check` — run Easy Coding Standard (`ecs.php`) with or without autofix to enforce PSR-12 plus the configured PhpCsFixer/PHPCS rules.
- `make rector` — execute Rector refactors (add rules to `rector.php` if needed).
- `make phpstan` — generate `var/phpstan/phpstan-report.json` with static-analysis findings.
- `make test` — run PHPUnit 12.5 with sane defaults (`APP_ENV=test`, Xdebug off, no coverage).
- `make before_push` — sequentially run ECS, Rector, PHPStan, and PHPUnit to replicate CI locally.

## Coding Style & Naming Conventions
- `.editorconfig` defines UTF-8, LF endings, and 4-space indentation; matchers and sanitizers should stay in `Solitus0\PayloadSanitizer\` with PascalCase class names and `Interface` suffixes for contracts.
- ECS enforces `declare(strict_types=1)`, PSR-12 imports, short arrays, single quotes, separated class members, and trimmed PhpDoc tags. Avoid tabs, keep line length ≤120, and let ECS autofix formatting before committing.

## Testing Guidelines
- Write PHPUnit tests under `tests/` mirroring namespaces; name files `*Test.php` and methods `test_it_does_x`.
- Prefer data providers for payload permutations and assert against the sanitized payload, as highlighted in `README.md`.
- When adding new matchers or sanitizers, include high-level tests plus edge cases (nulls, binary data) and keep fixtures lightweight arrays or JSON snippets.

## Commit & Pull Request Guidelines
- Existing history is terse (`in p`), so favor descriptive, action-oriented messages; Conventional Commit prefixes (`feat:`, `fix:`, `chore:`) keep changelogs readable.
- Reference linked issues, summarize behavior changes, and mention whether docs/tests were updated.
- Before opening a PR, run `make before_push`, describe validation steps, and attach payload examples or screenshots if behavior affects observability tooling.
