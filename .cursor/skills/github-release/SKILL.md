---
name: github-release
description: Tag a new version and publish a GitHub Release for nova-scheduled-jobs using GitHub's Generate release notes format. Use when the user asks to release, tag, cut a version, or publish release notes after changelog work is ready on master.
---

# GitHub release (tag + generated notes)

Automate publishing a GitHub Release for this repo so the notes match past releases (GitHub **Generate release notes**).

## Past format (must match)

Tag and release title are the bare semver (no `v` prefix). Body is GitHub auto-generated notes:

```markdown
## What's Changed
* <PR title> by @<author> in https://github.com/llaski/nova-scheduled-jobs/pull/<n>


**Full Changelog**: https://github.com/llaski/nova-scheduled-jobs/compare/<prev>...<tag>
```

When first-time contributors appear, GitHub also inserts a `## New Contributors` section — keep that; do not strip it.

Do **not** hand-write this body. Always use GitHub's generator (`gh release create --generate-notes` or `POST .../releases/generate-notes`).

## Conventions for this repo

| Item | Value |
| --- | --- |
| Tag | Bare semver: `6.1.4` (never `v6.1.4`) |
| Release name/title | Same as tag |
| Target | `master` |
| Notes | `--generate-notes` (GitHub Release Notes API) |
| Previous range | Previous published release tag (auto, or `--notes-start-tag` if needed) |
| Draft / prerelease | Published release unless the user asks for a draft or prerelease |
| `CHANGELOG.md` | Human changelog; update **before** tagging. It is **not** the GitHub release body |

## When to run

Only when the user explicitly asks to tag/release/publish. Never create a release as a side effect of other work.

Typical sequence the user already follows:

1. Land the release PR on `master` (including the `CHANGELOG.md` entry).
2. Ask the agent to tag + publish notes for that version.

## Preconditions (abort if any fail)

1. Working tree clean (or only unrelated local noise the user accepts).
2. `master` is up to date with `origin/master` and contains the intended release commit (changelog entry for the version).
3. Version string is known (user-provided, or top `## X.Y.Z -` heading in `CHANGELOG.md`).
4. No existing git tag or GitHub release for that version:
   - `git ls-remote --tags origin "<version>"`
   - `gh release view "<version>"` (expect failure / not found)
5. Confirm with the user: version, that `master` is the intended tip, and publish vs draft.

## Steps

### 1. Resolve version and previous tag

```bash
# Version: from user, else newest ## heading in CHANGELOG.md
# Previous: latest GitHub release tag
gh release view --json tagName -q .tagName
```

If the new version does not appear as the latest `##` section in `CHANGELOG.md`, stop and ask — do not invent changelog text.

### 2. Preview notes (same generator as the UI)

```bash
gh api repos/llaski/nova-scheduled-jobs/releases/generate-notes \
  -f tag_name="<version>" \
  -f target_commitish="master" \
  -f previous_tag_name="<previous>"
```

Show the user the returned `name` / `body`. Title for publish must still be the bare version (override auto title if different).

### 3. Create the tag + release

Prefer one command so the tag is created from `master` and notes come from GitHub:

```bash
gh release create "<version>" \
  --target master \
  --title "<version>" \
  --generate-notes \
  --notes-start-tag "<previous>"
```

Omit `--notes-start-tag` only when there is no previous release (first release) or GitHub’s default previous tag is already correct.

Optional:

- `--draft` — if the user wants to review in the UI before publish
- `--prerelease` — only if asked
- `--fail-on-no-commits` — recommended so empty ranges fail loudly

### 4. Verify and report

```bash
gh release view "<version>" --json url,tagName,name,isDraft,isPrerelease,body
```

Reply with the release URL and a short confirmation that notes used `--generate-notes`.

Fetch the new tag locally if useful: `git fetch --tags origin`.

## Cloud Agent / permissions caveat

Cursor Cloud Agents often have **read-only** `gh` for mutations. If `gh release create` fails with auth/permission errors:

1. Still run the **generate-notes** preview (read/API may work) and show the body.
2. Give the user the exact `gh release create ...` command to run where they have `contents: write` (local machine or a PAT/CI job).
3. Do not fake notes by rewriting CHANGELOG into the release body.

## Do not

- Prefix tags with `v`
- Hand-author `## What's Changed` bullets instead of `--generate-notes`
- Put `CHANGELOG.md` prose into the GitHub release body (unless the user explicitly asks to prepend highlights via `--notes`, which GitHub places **above** generated notes)
- Tag from a feature branch when the release is meant for `master`
- Retag or delete an existing release without an explicit user request
- Create a release without an explicit user request to release/tag

## Quick reference

```bash
PREV="$(gh release view --json tagName -q .tagName)"
VER="X.Y.Z"   # from user / CHANGELOG

gh api repos/llaski/nova-scheduled-jobs/releases/generate-notes \
  -f tag_name="$VER" -f target_commitish="master" -f previous_tag_name="$PREV"

gh release create "$VER" \
  --target master \
  --title "$VER" \
  --generate-notes \
  --notes-start-tag "$PREV" \
  --fail-on-no-commits
```
