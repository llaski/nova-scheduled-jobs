# Agent instructions

This repository is a Laravel Nova package that provides a Tool and a Card for viewing scheduled commands and jobs.

## Package overview

- **PHP**: `src/` — Tool, Card, HTTP routes/controllers, schedule helpers; auto-discovered via `ServiceProvider`.
- **Frontend**: Vue assets under `resources/js` / `resources/css`, built with Laravel Mix into `dist/`.
- **Tests**: PHPUnit feature tests under `tests/` (Orchestra Testbench).
- Requires PHP `^8.0` and Laravel Nova `^4.0` (Nova Composer credentials needed to install).

## Working in this repo

- Prefer small, focused changes. Match existing style and structure.
- Follow [CONTRIBUTING.md](CONTRIBUTING.md): PSR-2, add tests for behavior changes, update docs when behavior changes, SemVer-friendly public APIs.
- After PHP changes, run: `vendor/bin/phpunit --verbose`
- After frontend changes, rebuild assets (`npm run development` or `npm run production`) and commit updated `dist/` when that is part of the package’s release workflow.
- Do not commit secrets (Nova license credentials, passwords, tokens).

## Cursor Cloud specific instructions

### Nova UI screenshots

Before capturing screenshots or recordings of Nova:

- Dismiss Chrome’s “Save password?” (and similar) dialogs so they do not overlay the UI.
- Prefer a clean browser profile / disable password save if launching Chrome for verification.

## Shipping this file with the Composer package

**Recommendation: do not ship `AGENTS.md` to package consumers.** It is for people (and Cursor agents) developing this repository, not for apps that install the package via Composer.

This repo already uses Git `export-ignore` so Packagist / `composer archive` omit contributor-only files. Keep `AGENTS.md` excluded the same way in `.gitattributes`:

```gitattributes
AGENTS.md export-ignore
```

- **Keep it in git** so contributors and Cloud Agents still see these instructions.
- **Leave the `export-ignore` line in place** so `composer require` for this package does not install this file into `vendor/`.
- **To ship it in the package instead**, remove the `AGENTS.md export-ignore` line from `.gitattributes` and cut a new release (existing installs only pick that up after they update).
- **To stop shipping agent instructions entirely**, delete `AGENTS.md` from the repo (and remove its `export-ignore` line if present).
