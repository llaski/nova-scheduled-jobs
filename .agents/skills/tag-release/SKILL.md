---
name: tag-release
description: >-
  Tag and publish a GitHub Release with generate-notes.
  Use when the user asks to tag, release, or cut a version on master.
---

# tag-release

Cut a GitHub Release from `master`. Notes always come from GitHub **generate-notes** (same API as the UI button) so the body matches prior releases (`## What's Changed`, optional `## New Contributors`, `**Full Changelog**`).

## Conventions

| | |
| --- | --- |
| Tag + title | Bare semver (`6.1.4`) |
| Target | `master` |
| Notes | `gh release create --generate-notes` only |
| `CHANGELOG.md` | Must already list the version; never the release body |

Draft or prerelease only when the user asks.

## Steps

### 1. Confirm the cut

Resolve version (user, else top `## X.Y.Z -` in `CHANGELOG.md`). Confirm with the user: version, that `origin/master` is the tip to tag, and publish vs draft.

**Done when:** user confirmed those three.

### 2. Gate the cut

Abort unless all hold:

1. Working tree clean enough to proceed (or user accepts the noise).
2. Local `master` matches `origin/master` and includes the changelog entry for this version.
3. Top `CHANGELOG.md` heading is this version (ask if not — invent no changelog).
4. Tag and release absent: `git ls-remote --tags origin "<version>"` empty; `gh release view "<version>"` not found.

**Done when:** every gate passed.

### 3. Preview generate-notes

```bash
PREV="$(gh release view --json tagName -q .tagName)"
gh api repos/llaski/nova-scheduled-jobs/releases/generate-notes \
  -f tag_name="<version>" \
  -f target_commitish="master" \
  -f previous_tag_name="$PREV"
```

Show the returned body. Publish title stays the bare version even if GitHub suggests another name.

**Done when:** user has seen the preview.

### 4. Publish

```bash
gh release create "<version>" \
  --target master \
  --title "<version>" \
  --generate-notes \
  --notes-start-tag "$PREV" \
  --fail-on-no-commits
```

Add `--draft` / `--prerelease` only if confirmed in step 1. Omit `--notes-start-tag` only when there is no previous release.

If create fails on auth (common on Cloud Agents with read-only `gh`): keep the preview, hand the user that exact command, leave notes as generate-notes (no CHANGELOG paste).

**Done when:** release exists, or the user has the exact create command after an auth handoff.

### 5. Report

```bash
gh release view "<version>" --json url,tagName,name,isDraft,isPrerelease,body
```

Reply with the URL and that notes used generate-notes. `git fetch --tags origin` if a local tag is useful.

**Done when:** user has the release URL (or handoff command from step 4).
