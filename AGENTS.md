# AGENTS.md

Laravel Nova package: Tool + Card for scheduled commands/jobs. Nova Composer credentials are required to install.

## Commands

- Tests: `vendor/bin/phpunit --verbose`
- Frontend: `npm run development` / `npm run production` — commit updated `dist/` when releasing asset changes

## Conventions

Follow [CONTRIBUTING.md](CONTRIBUTING.md). Keep public API changes SemVer-safe.

## Cursor Cloud: Nova screenshots

Before capturing screenshots or recordings of Nova:

- Dismiss Chrome’s “Save password?” (and similar) dialogs so they do not overlay the UI.
- Prefer a clean browser profile / disable password save if launching Chrome for verification.

## Packaging

`AGENTS.md` is excluded from Composer installs via `.gitattributes` `export-ignore`. Leave that line in place. Maintainer how-to: [CONTRIBUTING.md](CONTRIBUTING.md#agentsmd).
