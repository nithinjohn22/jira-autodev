# Project conventions

## Branching
- Branch naming: `feature/<JIRA-KEY>-<short-slug>` (e.g. `feature/PROJ-123-fix-login`)
- Always branch from `main`

## Before opening a PR
- Run `composer test` — all tests must pass
- Run `composer lint` if available — fix any reported issues
- Keep changes scoped to the ticket; do not refactor unrelated code

## Code style
- Follow PSR-12
- Existing error handling lives in `src/Utils/` — match those patterns
- Never modify files under `/infra` without explicit instruction

## PR conventions
- Open as **draft** — never merge directly
- Reference the Jira ticket in the PR title: `[PROJ-123] Short description`
- Add a one-paragraph summary of what changed and why

## Off-limits
- Do not touch environment files (`.env`, `.env.*`)
- Do not delete or rename database migration files
- Do not change CI configuration files (`.github/workflows/*.yml`)
