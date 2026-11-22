# Modernization campaign (2026-11)

Goal: bring backup-manager up to date under strict semver. Do everything that is
backward compatible in a minor release first, then make the major break; then do
the same for the framework packages (`backup-manager/laravel`,
`backup-manager/symfony`). Contributors get credit for every PR and idea used.

This document is the campaign's durable record. A new session resumes from
**Status** and **Resume here**; everything else is reference.

## Status (2026-11-22)

| Milestone | State | Where |
|---|---|---|
| M0 Foundation: CI revived, plan | done | #200 |
| M1 3.2.0: Flysystem 2/3 support, AsyncAws, failure reporting | released 3.2.0 | #201 |
| Security patch 3.2.1: patched dependency floors, CI hardening | released 3.2.1 (from `3.x`) | #202 |
| M2 4.0.0: PHP 8.2, Flysystem 3, typed API | released 4.0.0 (from `master`) | #203 |
| M3a `backup-manager/laravel` | **in progress**: triage done; next core 3.3.0/4.1.0 socket, then laravel foundation | separate repo |
| M3b `backup-manager/symfony` | not started | separate repo |
| M4 close-out | not started | |

Core repo (`backup-manager/backup-manager`): 0 open PRs, 0 open issues.
Packagist lists 4.0.0, 3.2.1, 3.2.0.

## Resume here

1. Read **Working conventions** and **Security baseline** below; they apply to
   the framework repos too.
2. Start M3a (Laravel). Survey data and the plan are in **M3** below; re-check
   the open PR/issue lists first, they may have moved.
3. Release tags always need Shawn's explicit go (see **Authority**).

## Glossary

- **Flysystem major**: the installed major version of `league/flysystem`
  (v1, v2 or v3). v2 and v3 share most class names; v1 differs.
- **Flysystem instance**: an object from `league/flysystem` that performs file
  operations (`FilesystemInterface` in v1, `FilesystemOperator` in v2/v3).
- **Adapter package**: an optional Composer package providing a Flysystem
  storage backend (e.g. `league/flysystem-aws-s3-v3`). The user installs it;
  this package lists it under `suggest`.
- **Filesystem factory**: a class in `src/Filesystems/` implementing
  `BackupManager\Filesystems\Filesystem`. It reports which **filesystem type**
  it handles and builds a Flysystem instance from config. (Named `Filesystem`
  for historical reasons; it is a factory.)
- **Filesystem type**: the `type` value of a storage config entry (`Local`,
  `AwsS3`, `sftp`, ...), matched case-insensitively.
- **Connection name**: the key of a config entry in `storage.php` or
  `database.php` (`local`, `s3`, `production`, ...).
- **Version-aware factory** (3.x only): a filesystem factory that builds the
  adapter for whichever Flysystem major is installed.
- **Public API**: every public class, interface, public/protected method and
  its signature, config key, and filesystem/database type. An incompatible
  change requires a major release.
- **Framework package**: `backup-manager/laravel` or `backup-manager/symfony`,
  which wire this library into a framework.

## Branches and releases

- `master` is 4.x (`branch-alias dev-master: 4.x-dev`).
- `3.x` (cut from the 3.2.0 tag) receives 3.x fixes; release 3.x patches from it.
- Release procedure: date the CHANGELOG heading, `git tag -a X.Y.Z`, push the
  tag, `gh release create` with the CHANGELOG section as notes, then confirm on
  `https://repo.packagist.org/p2/backup-manager/backup-manager.json`.
- Supported lines: 4.x (PHP 8.2+, Flysystem 3) and 3.x (PHP 7.4+, Flysystem
  1/2/3, security fixes).

## Authority

- Claude may: push branches, open and merge PRs, comment on and close PRs and
  issues, change repo security settings.
- Release tags need Shawn's explicit go each time.

## Working conventions

- **One branch + worktree per milestone** (`campaign/<milestone>`,
  `../backup-manager-<milestone>`), one PR per milestone, merged by Claude.
  Remove the worktree and branch after merge.
- **CI is the test matrix** (Shawn's preference); no local matrix script. Push
  the branch and read the per-cell results, including the "Show resolved key
  packages" step to confirm each cell got the intended versions.
- **Local quick checks** (composer is not installed on the host; docker is):
  - `dcomposer`: `docker run --rm --network host -u $(id -u):$(id -g) -e COMPOSER_HOME=/tmp/ch -v "$PWD:/app" -w /app composer:2 composer ...`
    (PHP 8.5 with unzip). Add `--ignore-platform-req=ext-ftp`.
  - run specs on a version: mount the composer binary into `php:<v>-cli` and
    run `vendor/bin/phpspec run`. The stock `php:*-cli` images lack `ext-ftp`,
    so the two FTP adapter examples break locally only; CI has the extension.
  - static tools were installed into scratch dirs: PHPStan 2.3.7, Psalm 7.15
    (needs PHP 8.4+ to run), PHP-CS-Fixer 3.95.32.
  - zsh: never name a shell variable `path` (it is tied to `$PATH`).
- **Credit**: merge a valid PR as-is; otherwise reimplement it with
  `Co-authored-by: Name <id+login@users.noreply.github.com>` trailers (IDs via
  `gh api users/<login> -q .id`) and name the PR and author in CHANGELOG.md.
  Close the superseded PR with a comment saying how its work landed. Milestone
  PRs that carry co-author trailers on several commits are merged with
  `--merge` (keeps trailers); single-commit PRs may be squashed with the
  trailer in the body.
- **Issues**: reference, don't auto-close, in milestone PRs; close each issue
  with a comment once the fix is released.
- **Independent review**: before merging a release milestone, a fresh agent
  reviews the diff for BC breaks (minor) or undocumented breaks (major) and
  correctness. Both reviews in this campaign found real issues.

## Security baseline

Apply to every repo in the campaign:

- Dependency floors at releases patched for known CVEs. Check with
  `https://packagist.org/api/security-advisories/?packages[]=...` and
  `composer audit`. Current core floors: `league/flysystem ^3.35.3`
  (CVE-2026-102601), `symfony/process ^6.4.33 || ^7.4.5 || ^8.0.5`
  (CVE-2024-51736, CVE-2026-24739).
- Actions pinned to commit SHAs with a version comment; `.github/dependabot.yml`
  (github-actions, monthly) keeps them current. Current pins: checkout v7.0.1,
  setup-php 2.40.0 (fixes GHSA-5wxr-w449-57cm, GHSA-pqwm-q9pv-ph8r),
  composer-install 4.0.0.
- `permissions: contents: read` on workflows; `composer audit` job; weekly
  `schedule` trigger (runs on the default branch only).
- Repo settings: Dependabot alerts and security updates, secret scanning and
  push protection enabled (done for the core repo).
- Composer blocks advisory-flagged packages by default. Legacy test cells that
  must install them (3.x Flysystem 1/2) pass `--no-security-blocking`;
  consumer installs are unaffected.

## Milestones (done)

### M0: Foundation — #200
CI on current runners/actions; Psalm 7; Dependabot for actions; `.gitattributes`
(reimplements #171, fezfez).

### M1: 3.2.0 — #201
Version-aware factories for Local, Ftp, Sftp, AwsS3, Gcs, Webdav, Dropbox;
`FilesystemAdapterMissing`; `AsyncAwsS3`; MySQL `pdo_mysql`, `columnStatistics`,
`onlyTables`; Flysystem 1 transfer failures throw; storage tasks accept any
Flysystem instance; `symfony/process` 4.4–8; DropboxV2/Rackspace/B2 deprecated;
CHANGELOG, README. CI: 15 cells (Flysystem 1 on PHP 7.4–8.4, 2 on 7.4/8.1, 3 on
8.0–8.5, lowest and highest). Credits: meiyasan (#187, #191–#195), Nyholm (#168,
#185), Jelle-S (#196), fezfez (#171, onlyTables), DjThossi (#172 report).

### 3.2.1 — #202 (on `3.x`)
Patched `symfony/process` floors (drops 4.4, which has no fix) and Flysystem v3
floor 3.35.3; CI hardening.

### M2: 4.0.0 — #203
PHP ^8.2, Flysystem ^3.35.3, `symfony/process ^6.4.33 || ^7.4.5 || ^8.0.5`;
removed DropboxV2, Rackspace, B2 and all Flysystem 1/2 paths; native types,
`strict_types`, `#[\Override]`, final concrete factories/databases/compressors/
tasks; `FlysystemFilesystem` takes named instances (MountManager by name);
MySQL restore escapes the path; PHPStan 2 level 8 and Psalm 7 level 2 without
baselines; PSR-12. `UPGRADE.md` documents every break.

## M3: Framework packages (next)

Both packages pin `backup-manager/backup-manager: ^3.0`, have no CI, and have
been unmaintained for years. Both register `DropboxV2Filesystem` and
`RackspaceFilesystem` by class name (Symfony also `BackblazeFilesystem` and
validates `B2`/`Rackspace`/`DropboxV2` in its `Configuration`), so they cannot
require core ^4.0 without a major release of their own.

Plan per package, same semver discipline as core:
1. **Foundation**: CI (matrix over supported framework and PHP versions),
   security baseline, campaign section in that repo's docs.
2. **Minor release**: support current framework versions on core `^3.2.1`
   without dropping anything; review and merge or reimplement open PRs; fix
   bugs from issues; CHANGELOG.
3. **Major release**: require core `^4.0`; drop the removed filesystem types
   and EOL framework/PHP versions; UPGRADE notes.
4. Close every PR and issue with a comment.

PR and issue triage is part of each framework package's work, as it was for
core: every open PR and issue is read against the current code and gets a
recorded decision (fix/merge, reimplement with credit, answer, or decline with
a reason) in that package's triage table below, before the minor release is
planned. Feature requests that fit are scheduled into the minor or major.

### M3a: `backup-manager/laravel` (survey 2026-11-22)
- Latest tag 2.0 (tags: 2.0, 1.4.0, 1.3.1). Constraints: PHP `^7.3||^7.4`,
  `illuminate/* ^5.5||^6||^7`, `symfony/process ^3||^4||^5`.
- Service providers `Laravel55ServiceProvider`, `Lumen55ServiceProvider`
  register filesystems, `MysqlDatabase`, `GzipCompressor`; default config ships
  `Rackspace`/`DropboxV2` entries.
- Open PRs: #135 (martindrapeau) PHP 8 support in AutoComplete.php; #130
  (nafiesl) Laravel 8 support and beyond.
- Open issues (21): #136 laravel; #132 Abandoned?; #131 Lumen 7.2.2 error;
  #129 dropbox not working; #128 crash when DB uses a socket (no host); #125
  Laravel 7; #124 delete a backup; #121 install error; #120 GCS backup error;
  #116 changing configuration of original library; #115 config from DB table;
  #107 MongoDB; #102 DigitalOcean Spaces; #101 single-transaction flag; #99
  table separation; #98 back up storage directory; #97 Aws S3Client not found;
  #95 PHP 7.1; #88 Laravel51Compatibility::table signature; #85 empty
  password; #82 dynamic S3 bucket; #78 no database selected with dynamic DB.
- Current Laravel (2026-11-22): 12 (PHP ^8.2, symfony ^7.2) and 13 (PHP ^8.3,
  symfony ^7.4 || ^8.0). Core ^3.2.1 needs `symfony/process ^5.4.51`, so
  Laravel 6 and older can no longer resolve; 7–13 can.

#### M3a triage (2026-11-22)

Defects found by reading the code (no issue filed):
- D1: `db:list` and interactive `db:restore` read Flysystem 1 listing keys
  (`basename`, `size`, `timestamp`); on Flysystem 2/3 (what core 3.2 installs)
  they fail. Fix: read v1 and v2/v3 attributes.
- D2: `Lumen55ServiceProvider` does not register `GcsFilesystem` (#120).
- D3: commands receive procedures/providers in their constructors at console
  boot, so config changed at runtime is ignored (#78, #82). Fix: resolve them
  in `handle()`.
- D4: non-MySQL/PostgreSQL connections map to `null` config entries, giving
  "Could not find field type" (#107) instead of "not supported".

| # | Kind | Decision | Release |
|---|---|---|---|
| PR #135 martindrapeau | PHP 8 signature fix | reimplement with credit (conflicts with #130 composer change) | 2.1 |
| PR #130 nafiesl | Laravel 8+ | reimplement with explicit ranges, not `>=5.5`; credit | 2.1 |
| #136 | spam | close | now |
| #132 | abandoned? | answer with 2.1 release | 2.1 |
| #131 | Lumen `new Process('')` on symfony/process 5 | fix | 2.1 |
| #129 | Dropbox silently no file | DropboxV2 deprecated in core; config default switches to `Dropbox`; close with pointer | 2.1 |
| #128 | MySQL socket, no `host` | core gets a MySQL `socket` key (3.3.0, 4.1.0); package maps `unix_socket` and makes `host` optional | core 3.3.0 + 2.1 |
| #125 | Laravel 7 | already in 2.0; close | now |
| #124 | delete old backups | answer (retention is out of scope; Flysystem delete example) | 2.1 |
| #121 | `Config(null)` with cached config | fix (fall back to `[]`, clear error) | 2.1 |
| #120 | Gcs on Lumen | fix (D2) | 2.1 |
| #116, #101, #99 | singleTransaction, ignoreTables, onlyTables, ssl, columnStatistics | fix: pass these keys through from the Laravel DB connection | 2.1 |
| #115 | config from DB table | answer: set config at runtime before resolving (works after D3) | 2.1 |
| #107 | MongoDB | decline (core supports MySQL/PostgreSQL); clearer error (D4) | 2.1 |
| #102 | DigitalOcean Spaces | core 3.x reads `endpoint`/`use_path_style_endpoint`; document in default config | 2.1 |
| #98 | back up storage directory | decline: out of scope (database backups) | now |
| #97 | `Aws\S3\S3Client` not found | core 3.2 throws `FilesystemAdapterMissing` naming the package; close | 2.1 |
| #95 | Dropbox on PHP 7.1 | stale (Laravel 5.5); close with pointer | 2.1 |
| #88 | `table()` signature | fixed in 2.0 (#91, whoacowboy); close | now |
| #85 | empty password | core 3.x omits empty `pass` for MySQL; close | 2.1 |
| #82, #78 | runtime config ignored | fix (D3) | 2.1 |

### M3b: `backup-manager/symfony` (survey 2026-11-22)
- Latest tag 3.2.1 (also v1.1, v1.0). Constraints: PHP `^7.3 || ^8.0`,
  `symfony/* ^3.4 || ^4.4 || ^5.0 || ^6.0`, `nyholm/dsn ^2.0`.
- Services defined in `Resources/config/services.yml` by class name.
- Open PRs: none. Open issues (6): #91 Flysystem v2; #90 release 3.2.2 (is it
  dead?); #89 PHP 8.1 + Symfony 6; #88 config not loaded with Flex; #84
  AsyncAWS instead of AWS SDK; #78 error on DSN.

## M4: Close-out
- Every PR and issue across the three repos closed with a comment.
- Move the glossary to `docs/GLOSSARY.md`.
- Final summary of releases and credits.

## Decisions log

- 2026-11-22: Authority as above.
- 2026-11-22: 4.0 keeps the provider architecture and the `Filesystem` factory
  interface; Nyholm's Flysystem-native proposal (#185) declined.
- 2026-11-22: CI is the test matrix; no local matrix script.
- 2026-11-22: 3.x minor/patch releases never narrow the public API; dependency
  ranges are narrowed only to exclude versions with known CVEs (3.2.1 dropped
  `symfony/process` 4.4, which has no patched release).
- 2026-11-22: GHSA-cxf4-7mrp-vvpr (CVE-2026-102601) flags `league/flysystem
  <= 3.35.2`, i.e. all of v1 and v2; phpseclib 2.x is also fully flagged.
  Composer blocks these by default, so 3.1.0 installs resolved Flysystem 3 and
  fataled; that made 3.2.0 urgent. 3.x keeps v1/v2 (removing them is a break);
  4.0 requires 3.35.3.
- 2026-11-22: `3.x` branch created from the 3.2.0 tag; master is 4.x.
- 2026-11-22: In 3.2, Gcs on Flysystem 1 reads only `prefix` (as 3.1 did) so
  existing backup paths do not move; v2/v3 and 4.0 accept `prefix` then `root`.
- 2026-11-22: 4.0 drops the `FlysystemFilesystem` mount-manager prefix lookup:
  a v3 `MountManager` is a `FilesystemOperator` and is registered by name.
- 2026-11-22: 4.0 Psalm runs at level 2 with `findUnusedCode=false` (library
  API looks unused) and `ClassMustBeFinal` suppressed (providers, exceptions,
  `Config`, `Manager`, procedures, `ShellProcessor`, `Sequence` stay
  extensible; specs double several of them).
- 2026-11-22: phpspec `^7.5 || ^8.0` covers PHP 7.4–8.5 on 3.x; `^8.0` on 4.x.
- 2026-11-22: Issue #167 (Docker image) declined: out of scope for a library.
- 2026-11-22: Framework repos get the same PR/issue triage as core (Shawn).
- 2026-11-22: laravel #128: add a MySQL `socket` key to core (3.3.0 on `3.x`,
  4.1.0 on master) rather than an extraParams workaround (restore would miss it).
- 2026-11-22: laravel versions: 2.1.0 on core ^3.2.1 (after 3.3.0: ^3.3), 3.0.0
  on core ^4.1. 3.0 drops the Lumen provider (Lumen is discontinued).
- Local clone of the laravel repo: `/home/shawn/src/backup-manager/laravel`.
