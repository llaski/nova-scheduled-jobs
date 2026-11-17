# Browse scheduled jobs

Browse scheduled jobs shows every registered schedule event in Nova: a compact **Card** on the main dashboard and a full **Tool** page with schedule metadata and flags.

## Sub-features

- `browse-card` shows the Main dashboard card titled Scheduled Jobs. Demo host uses `only(['command', 'nextDue'])` (expression hidden).
- `browse-tool` shows the Tool page table. Demo host uses `except(['timezone', 'runInBackground', 'evenInMaintenanceMode'])`; remaining columns include schedule, expression, next due, overlap / one-server flags, and Dispatch.
- `browse-empty` is out of scope for the seeded demo (empty copy exists in UI when the schedule has no events).

## How to get to it (user POV)

- Sign in to Nova, land on Main dashboard, read the Scheduled Jobs card.
- Open **Scheduled Jobs** in the Nova sidebar (or go to `/nova/nova-scheduled-jobs`).

## Driving it with drive.mjs

Preconditions:

- `helpers/doctor.sh` exits 0.
- Seeded schedule includes at least `inspire`, `demo:heartbeat`, an `App\Jobs\*` class, and timezone `America/New_York` on `demo:send-digest`.

- **Login.** Open `/nova/login`, submit `admin@example.com` / `password`. Run `helpers/drive.mjs --feature browse-scheduled-jobs`. Browser reaches `/nova/dashboards/main`.
- **Card list.** On Main, locate the Scheduled Jobs card table. Observable: headers Name and Next Due only (no Expression in the demo); rows include `php artisan inspire` (or `inspire`) and at least one `App\Jobs\` command; Next Due shows human-readable relative times. Evidence: `browse-card.png`.
- **Open tool.** Choose sidebar Scheduled Jobs (or navigate to `/nova/nova-scheduled-jobs`). Observable: heading Scheduled Jobs; demo columns include Schedule, Expression, Next Due, Without Overlapping, On One Server, Dispatch — and do **not** include Timezone, Run In Background, or Run In Maintenance Mode.
- **Flag rows.** Confirm a row with Without Overlapping Yes (`demo:sync-metrics`) and On One Server Yes (`GenerateDemoReport`). Timezone / background / maintenance flags remain in the API payload (`browse-jobs.json`) even when those columns are hidden. Evidence: `browse-tool.png` plus `browse-jobs.json` (API payload count ≥ demo schedule size).

## Gotchas

- Card and Tool share `/nova-vendor/nova-scheduled-jobs/jobs` but different columns — do not treat a card screenshot as tool proof.
- Job `command` strings differ by event type (artisan string, FQCN, closure label, exec string). Assert presence, not a single format.
- The Tool auto-refetches every 60s; a mid-refresh empty flash is not an empty schedule.
- Nova 5 login uses Fortify; wait for navigation off `/nova/login` before asserting the dashboard.
