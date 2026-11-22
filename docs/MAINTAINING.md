# Maintaining

How this package and its framework packages (`backup-manager/laravel`,
`backup-manager/symfony`) are versioned, released and kept secure. Terms are
defined in [GLOSSARY.md](GLOSSARY.md).

## Supported versions

| Package | Active (`master`) | Security fixes until 2027-12-31 | Unsupported |
|---|---|---|---|
| backup-manager/backup-manager | 4.x | 3.x (branch `3.x`) | 2.x, 1.x |
| backup-manager/laravel | 3.x | 2.x (branch `2.x`) | 1.x |
| backup-manager/symfony | 4.x | 3.x (branch `3.x`) | older |

Fix a bug on the oldest supported line it affects and release from that
line's branch, then carry the fix forward to `master`.

## Versioning

- Strict semver. An incompatible change to the public API requires a major
  release. Do everything backward compatible in a minor release before a
  major.
- Minor and patch releases never narrow the public API. A dependency range is
  narrowed only to exclude versions with known CVEs (see **Security
  baseline**), and the narrowing is stated in the changelog.

## Release procedure

1. Date the changelog heading on the release branch.
2. Create an annotated tag `X.Y.Z` on the merge commit and push it (or create
   it through the GitHub API: `git/tags`, then `git/refs`).
3. `gh release create X.Y.Z --verify-tag` with the changelog section as
   notes; pass `--latest=false` for releases from a previous-major branch.
4. Confirm the version on `https://repo.packagist.org/p2/backup-manager/<package>.json`.
5. Close the issues the release fixes, each with a comment.

## Contributions and credit

- Merge a valid PR as it is. Otherwise reimplement it with
  `Co-authored-by: Name <id+login@users.noreply.github.com>` trailers (IDs
  via `gh api users/<login> -q .id`), name the PR and author in the
  changelog, and close the superseded PR with a comment saying how its work
  landed.
- A PR whose commits carry co-author trailers is merged with `--merge` to
  keep them; a single-commit PR may be squashed with the trailer in the body.
- Before merging a release, have the diff reviewed independently for
  compatibility breaks (minor) or undocumented breaks (major) and for
  correctness.

## Testing

- CI is the test matrix: push a branch and read the per-cell results,
  including the "Show resolved key packages" step, which shows the versions
  each cell installed. There is no local matrix script.
- The framework packages' CI fails on deprecations (PHPUnit
  `failOnDeprecation`).
- Static analysis: PHPStan level 8, Psalm level 2 (core 3.x uses a baseline),
  PHP-CS-Fixer (PSR-12), composer-normalize.
- Locally, Composer can run from Docker:
  `docker run --rm --network host -u $(id -u):$(id -g) -e COMPOSER_HOME=/tmp/ch -v "$PWD:/app" -w /app composer:2 composer ...`
  (add `--ignore-platform-req=ext-ftp`). The stock `php:*-cli` images lack
  `ext-ftp`, so the FTP adapter examples fail locally only.

## Security baseline

Applies to all three repositories.

- Dependency floors are set at releases patched for known CVEs. Check with
  `https://packagist.org/api/security-advisories/?packages[]=...` and
  `composer audit`. Framework packages require the latest core patch release
  of their line.
- Legacy CI cells that can only install advisory-flagged versions (e.g.
  Laravel 7–11) run with `audit.block-insecure false`; the package still
  declares those versions until its next major.
- GitHub Actions are pinned to commit SHAs with a version comment;
  Dependabot (`.github/dependabot.yml`, github-actions) keeps them current.
- Workflows declare `permissions: contents: read`, run a `composer audit`
  job, and run weekly on a schedule (default branch only).
- Repository settings: Dependabot alerts and security updates, secret
  scanning and push protection.

## Standing decisions

- 4.x keeps the provider architecture and the `Filesystem` factory interface
  rather than exposing Flysystem directly (#185 declined).
- 3.x keeps Flysystem v1 and v2 support even though every v1/v2 release is
  advisory-flagged; removing it would be a break. 4.x requires Flysystem 3.
- MySQL restores read the dump from stdin (`mysql ... 'db' < 'path'`); the
  file path is never passed to the mysql client (fixed in 3.3.1 and 4.1.1).
- A Docker image is out of scope for a library (#167 declined).
- The Laravel package reads its configuration at use time (`RuntimeConfig`),
  so configuration changed at runtime takes effect. A connection's `url`
  (`DB_URL`) is parsed with Laravel's `ConfigurationUrlParser`; a malformed
  `url` on any connection makes every backup fail.
- Old unmerged branches were removed in 2026-11; their tips are kept as
  `archive/<branch>` tags.
