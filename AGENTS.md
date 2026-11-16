# AGENTS.md

Laravel Nova package: Tool + Card for scheduled commands/jobs. Nova Composer credentials are required to install.

## Commands

- Tests: `vendor/bin/phpunit`
- Frontend: `npm run development` / `npm run production` — commit updated `dist/` when releasing asset changes

## Conventions

Follow [CONTRIBUTING.md](CONTRIBUTING.md). Keep public API changes SemVer-safe.

## Cursor Cloud: Nova screenshots

Before capturing screenshots or recordings of Nova:

- Dismiss Chrome’s “Save password?” (and similar) dialogs so they do not overlay the UI.
- Prefer a clean browser profile / disable password save if launching Chrome for verification.

## Verification skills

- `.agents/skills/create-verification-skill` — generate/refresh a project verify skill (pstack)
- `.agents/skills/maintain-verification-skill` — audit the verify skill + feature map (pstack)
- `.cursor/skills/verify-nova-scheduled-jobs` (also linked from `.agents/skills/`) — drive the demo Nova Tool/Card and Dispatch UI

Package tooling under `.agents/` and `.cursor/` is `export-ignore`d and is not shipped to Composer consumers.

## Packaging

Do not ship agent tooling to Composer consumers — `AGENTS.md` and `.agents/` are `export-ignore`d in `.gitattributes`. Leave those lines in place.

- To ship either: remove its `export-ignore` line and cut a new release.
- To drop either: delete the file/dir and remove its `export-ignore` line.
