# Dispatch a job

Dispatch lets a Nova user run a scheduled **Job class** immediately from the Tool: enable the play control only for `\Jobs` commands, confirm in a modal, and see a success toast (or a validation error for invalid commands).

## Sub-features

- `dispatch-enable` enables the Dispatch button only when `command` contains `\Jobs`.
- `dispatch-cancel` opens the confirm modal and dismisses without posting.
- `dispatch-confirm` posts the job and shows success feedback.
- `dispatch-reject-non-job` keeps artisan/exec/closure Dispatch controls disabled (no POST).

## How to get to it (user POV)

- Open the Scheduled Jobs tool.
- On a Job class row (e.g. `App\Jobs\PruneDemoRecords`), choose the Dispatch (play) control.
- In **Dispatch Job**, choose **Confirm** or **Cancel**.

## Driving it with drive.mjs

Preconditions:

- `helpers/doctor.sh` exits 0.
- Tool lists at least one `App\Jobs\` row with an enabled Dispatch button.
- Queue connection may be `sync` (job runs inline) or a queue worker; either is fine — proof is the UI toast and HTTP status, not queue depth.

- **Open tool.** Login and open `/nova/nova-scheduled-jobs`. Run `helpers/drive.mjs --feature dispatch-job`.
- **Non-job disabled.** Locate a `php artisan` row. Observable: its `button[aria-label="Dispatch"]` is disabled. Evidence note in `dispatch-job.json`.
- **Open confirm.** On `App\Jobs\PruneDemoRecords` (or another `App\Jobs\*` row), click the enabled Dispatch button. Observable: modal with title Dispatch Job and the FQCN in bold. Evidence: `dispatch-modal.png`.
- **Cancel path.** Choose Cancel (`[data-testid=cancel-button]`). Observable: modal closes; no success toast; no successful dispatch POST (or POST not sent).
- **Confirm path.** Re-open Dispatch on the same job, choose Confirm (`button[type=submit]` / dusk `confirm-delete-button`). Observable: toast/message `The job was dispatched!`; `POST /nova-vendor/nova-scheduled-jobs/dispatch` returns success (2xx). Evidence: `dispatch-success.png` and `dispatch-job.json` with status code.
- **Proof.** Screenshots show Nova chrome and the toast or post-dispatch tool page; JSON records command dispatched and HTTP status.

## Gotchas

- Enablement is a substring check on `\Jobs` in the command string — not `instanceof ShouldQueue`. Closures mentioning Jobs in a label could theoretically match; demo data does not.
- Modal heading may render as uppercase `DISPATCH JOB` in the accessibility tree; match case-insensitively.
- Confirm/Cancel are Nova custom elements (`loadingbutton` / `linkbutton`). Prefer `[data-testid=cancel-button]` and `form.requestSubmit()` over native `button.click()`.
- Confirming a non-existent / non-resolvable class returns 422 with message `{command} is not a valid job.` — do not use that as the happy path.
- Nova may also surface 500s via its global request error toast; treat that as product failure, not harness flakiness.
- Clicking Dispatch on a disabled control is a no-op; assert `disabled` rather than expecting a modal.
