---
name: verify-nova-scheduled-jobs
description: "Drive the llaski/nova-scheduled-jobs Nova Tool and Card in the laravel-nova-demo host: list scheduled jobs and dispatch Job classes from the tool UI. Use when proving package behavior after upgrades, UI changes, or before release."
---

# Verify nova-scheduled-jobs

Agent-facing control skill for the Nova Scheduled Jobs package. Primary surface is the **Laravel Nova admin UI** (Tool + Card) hosted by the sibling `laravel-nova-demo` app. Secondary surface: the package HTTP API under `/nova-vendor/nova-scheduled-jobs/*` (same data the UI uses).

## Launch

Host app: `../laravel-nova-demo` (path-linked package `@dev`). Requires PHP 8.3+, Nova Composer credentials (`auth.json` or `COMPOSER_AUTH`), and a seeded admin.

```bash
# From laravel-nova-demo (once per environment)
composer install
cp -n .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate:fresh --seed --force

# Start (keep alive for the run)
php artisan serve --host=127.0.0.1 --port=8000
```

Ready when `GET http://127.0.0.1:8000/nova/login` returns HTTP 200 and the HTML contains `Welcome Back` / Nova login form. Seeded credentials: `admin@example.com` / `password`.

Teardown: stop the `artisan serve` process you started (by PID), do not `pkill -f`. Leave evidence under the Evidence path.

Two instances: bind a second serve to another port and set `BASE_URL` accordingly. Do not double-drive one shared Nova session (cookies collide).

Helper:

```bash
.cursor/skills/verify-nova-scheduled-jobs/helpers/launch.sh
```

## Doctor

Read-only health check before every drive wave:

```bash
.cursor/skills/verify-nova-scheduled-jobs/helpers/doctor.sh
```

Requires: process answering on `BASE_URL` (default `http://127.0.0.1:8000`), login page 200, `composer show` in the demo reporting `laravel/nova` ^5 and path-linked `llaski/nova-scheduled-jobs`, and `php artisan schedule:list` showing demo events. Exit non-zero if any check fails — do not drive.

## Drive

Prefer the Puppeteer helper (real browser path). Fall back to authenticated HTTP only when proving the API contract, not as a substitute for UI proof of Dispatch.

```bash
# List tool + card (default feature)
.cursor/skills/verify-nova-scheduled-jobs/helpers/drive.mjs --feature browse-scheduled-jobs

# Dispatch a Job class from the tool
.cursor/skills/verify-nova-scheduled-jobs/helpers/drive.mjs --feature dispatch-job
```

Stable handles (prefer these over coordinates):

| Handle | Meaning |
| --- | --- |
| URL `/nova/login` | Login |
| `input[type=email]`, `input[type=password]` | Credentials |
| URL `/nova/dashboards/main` | Main dashboard (card) |
| URL `/nova/nova-scheduled-jobs` | Tool page |
| Sidebar text `Scheduled Jobs` | Tool nav |
| Table heading / card title `Scheduled Jobs` | List surfaces |
| `button[aria-label="Dispatch"]` (enabled) | Open dispatch confirm |
| Modal text `Dispatch Job` / `Confirm` / `Cancel` | Confirm or abort |
| Toast / success text `The job was dispatched!` | Dispatch succeeded |
| `[data-testid=cancel-button]` / form `requestSubmit()` | Cancel / Confirm in modal (custom elements) |

`canDispatchCommand` enables Dispatch only when `job.command` contains `\Jobs` (e.g. `App\Jobs\PruneDemoRecords`). Artisan/exec/closure rows stay disabled.

Feature recipes: `features/README.md`.

## Evidence

Write proofs under:

- Cloud / this agent: `/opt/cursor/artifacts/verify-nova-scheduled-jobs/`
- Local default: `.cursor/skills/verify-nova-scheduled-jobs/artifacts/` (gitignored scratch; copy keepers out)

Proof standards:

- Exercise the real Nova UI path (login → dashboard/tool), not only `GET .../jobs`.
- Capture **action + resulting state** (e.g. click Dispatch → Confirm → toast + network 2xx), not only a final screenshot.
- For Dispatch, also confirm the POST `/nova-vendor/nova-scheduled-jobs/dispatch` response (status 200/204) and that a non-Job command cannot be dispatched (button disabled or 422).
- Screenshots must show Nova chrome (sidebar or “Laravel Nova”) so the surface is identifiable.
- Prefer PNG + a small JSON/log of API payloads or network status alongside.

## Cleanup

- Stop only the serve PID this run started (see `helpers/launch.sh` pid file).
- Remove temporary browser profiles under `/tmp/nova-scheduled-jobs-verify-*` if created.
- **Never** delete `/opt/cursor/artifacts/verify-nova-scheduled-jobs/` or named proof files.

```bash
.cursor/skills/verify-nova-scheduled-jobs/helpers/cleanup.sh
```

## Helpers

| Script | Role |
| --- | --- |
| `helpers/launch.sh` | migrate/seed if needed; start `artisan serve`; write pid file |
| `helpers/doctor.sh` | read-only readiness |
| `helpers/drive.mjs` | Puppeteer login + feature drives; writes evidence |
| `helpers/cleanup.sh` | stop serve from pid file; wipe temp profiles only |

All helpers are executable. Invoke from the **package repo root** (`nova-scheduled-jobs`) unless noted. Set `DEMO_DIR` if the demo is not at `../laravel-nova-demo`. Set `BASE_URL` if not on `:8000`. Set `EVIDENCE_DIR` to override the evidence path.
