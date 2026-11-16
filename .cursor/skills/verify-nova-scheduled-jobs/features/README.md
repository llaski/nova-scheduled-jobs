# nova-scheduled-jobs verification map

Maintained source for verifying user-facing behavior of the Nova Scheduled Jobs **Tool** and **Card**. Read this index before driving, then follow the matching feature file.

## Baseline preconditions

- Demo host `laravel-nova-demo` is running at `BASE_URL` (default `http://127.0.0.1:8000`).
- Package is path-linked (`llaski/nova-scheduled-jobs` `@dev`) into that host on a Nova 5 / Laravel 12+ stack.
- Database migrated and seeded: Nova user `admin@example.com` / `password`.
- Demo schedule includes artisan commands, `App\Jobs\*` classes, a named closure, and an `exec` event (see demo `routes/console.php`).
- Run `helpers/doctor.sh` and require exit 0 before driving.
- Never drive a Nova session you did not authenticate in this run.

## Driving conventions

- Start every recipe from a fresh login unless the feature says otherwise.
- Prefer route URLs, `aria-label`, and visible Nova copy over CSS position.
- Treat helper CLI flags as literal.
- Drive the UI through `helpers/drive.mjs`; use raw HTTP only for API-side assertions called out in a recipe.
- After Dispatch mutations, leave the job list as-is (scheduling is read-only); no fixture restore needed beyond closing the modal.

## Proof and skip reporting

- Capture the user action and the resulting state, not only the final screen.
- UI proof includes screenshots with Nova identity visible and a short JSON/log of network outcomes where relevant.
- Mutation proof for Dispatch includes the success toast (or error) and the HTTP status of `POST .../dispatch`.
- Record the feature ID with every artifact filename prefix.
- Report an unreachable path with the attempted URL/control and the unmet precondition.
- Do not report a skipped entry point as verified through a different path.

## Feature entry contract

Each feature file starts with an H1 and one paragraph of user-visible behavior, then exactly four H2 sections: `Sub-features`, `How to get to it (user POV)`, `Driving it with drive.mjs`, `Gotchas`.

## Features

- [Browse scheduled jobs](./browse-scheduled-jobs.md) — Tool table and dashboard Card list the schedule.
- [Dispatch a job](./dispatch-job.md) — From the Tool, confirm or cancel dispatching an `App\Jobs\*` row.
