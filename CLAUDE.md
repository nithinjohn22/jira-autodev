# Project conventions

## Branching
- Branch naming: `feature/<JIRA-KEY>-<short-slug>` (e.g. `feature/PROJ-123-fix-login`)
- Always branch from `main`

## Before opening a PR
- Run `composer test` — all tests must pass
- Run `composer lint` to syntax-check src/ and tests/
- Keep changes scoped to the ticket; do not refactor unrelated code

## Code style
- Follow PSR-12
- Autoloader: `App\` maps to `src/`, `App\Tests\` maps to `tests/`
- Error handling patterns live in `src/Utils/ErrorHandler.php` — match those patterns for new error wrapping

## PR conventions
- Open as **draft** — never merge directly
- Reference the Jira ticket in the PR title: `[PROJ-123] Short description`
- Add a one-paragraph summary of what changed and why

## Off-limits
- Do not touch `.env` or `.env.*` files
- Do not delete or rename files under `tests/` without adding equivalent replacements
- Do not modify `.github/workflows/*.yml`
- Do not modify `composer.json` or `phpunit.xml` unless the ticket explicitly requires it
