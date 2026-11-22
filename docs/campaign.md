# Modernization campaign (2026-11)

Goal: bring backup-manager up to date under strict semver. Do everything that is
backward compatible in a minor release first, then make the major break; then do
the same for the framework packages (`backup-manager/laravel`,
`backup-manager/symfony`). Contributors get credit for every PR and idea used.

This document is the campaign's durable record. A new session resumes from
**Status** and **Resume here**; everything else is reference.

## Status (2026-11-22, after laravel 2.1.0)

| Milestone | State | Where |
|---|---|---|
| M0 Foundation: CI revived, plan | done | #200 |
| M1 3.2.0: Flysystem 2/3 support, AsyncAws, failure reporting | released 3.2.0 | #201 |
| Security patch 3.2.1: patched dependency floors, CI hardening | released 3.2.1 (from `3.x`) | #202 |
| M2 4.0.0: PHP 8.2, Flysystem 3, typed API | released 4.0.0 (from `master`) | #203 |
| Core 3.3.0 / 4.1.0: MySQL `socket` | released 3.3.0 (from `3.x`), 4.1.0 (from `master`) | #204, #205 |
| M3a laravel 2.1.0: Laravel 7–13, fixes, CI, badges | released 2.1.0 | laravel#138 |
| M3a laravel 3.0.0: core ^4.1, Laravel 12/13, renamed classes | merged to master (reviewed, CI green); **awaiting Shawn's go to tag 3.0.0** | laravel#139 |
| M3b symfony 3.3.0 (minor, core ^3.3) | merged to `3.x` (reviewed; floors at patched Symfony 5.4.56/6.4.50/7.4.23/8.1.11); **awaiting Shawn's go to tag 3.3.0 from `3.x`** | symfony#92, #93 |
| M3b symfony 4.0.0 (major, core ^4.1) | PR open, CI green, in review | symfony#94 |
| M4 close-out | not started | |

Core repo: 0 open PRs, 0 open issues; Packagist lists 4.1.0, 3.3.0.
Laravel repo: 0 open PRs, 0 open issues (all triaged and closed with
comments); Packagist lists 2.1.0.

## Resume here

1. Read **Working conventions** and **Security baseline** below; they apply to
   the framework repos too.
2. Next: on Shawn's go, tag laravel 3.0.0 (master), symfony 3.3.0 (`3.x`)
   and symfony 4.0.0 (master, after #94 merges); date each changelog heading
   first; then close symfony issues #78 #84 #88 #89 #90 #91 with comments,
   then M4 close-out.
3. Release tags always need Shawn's explicit go (see **Authority**).
4. Merges, tags and `gh release create` are allowed in
   `.claude/settings.local.json` of the core checkout (git-excluded); Claude
   Code's auto-mode classifier blocked them without it, and blocks Claude from
   writing permission rules itself.

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

#### M3a: laravel 3.0 plan
- Require `backup-manager/backup-manager ^4.1`, PHP ^8.2, Laravel 12 and 13
  only (Shawn, 2026-11-22), with `illuminate/*` floors at the patched
  releases (^12.71.0 || ^13.40.0, per CVE-2026-128083 and earlier). Remove
  `Lumen55ServiceProvider`, and the `DropboxV2` and `Rackspace` registrations
  and default config entries.
- Rename (Shawn, 2026-11-22): `BackupManagerServiceProvider`, one final class
  per command (`DbBackupCommand`, `DbRestoreCommand`, `DbListCommand`);
  `Laravel55*` names remain as deprecated `class_alias`es, removed in 4.0.
  Traits `AutoComplete`, `Laravel55Compatibility`, `GetDatabaseConfig` go.
- `2.x` branch (from tag 2.1.0) receives 2.x fixes.
- Native types, `strict_types`, PHPStan/Psalm like core 4.0; UPGRADE.md.
- Consider renaming `Laravel55*` classes (keep aliases or document in UPGRADE).
- The `db:restore` file prompt (`askSourcePath`) has no test; add one.
- Laravel 7–11 CI cells disable advisory blocking; 3.0 drops them.

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

#### M3b triage (2026-11-22)

Code survey (master 9aa06b8, unreleased since 3.2.1: Symfony 6 support #87,
`extraParams` #82, PHP 8.0 constraint #83):
- Bundle `BMBackupManagerBundle`, `BMBackupManagerExtension`,
  `Configuration` (storage types validated against a fixed list), services in
  `Resources/config/services.yml`, `ConfigFactory` (DSN via nyholm/dsn),
  commands `backup-manager:backup` / `backup-manager:restore`. PHPUnit tests
  exist (nyholm/symfony-bundle-test); CI was Travis (dead).
- D1: `BMBackupManagerExtension::validateStorage()` checks Flysystem 1
  adapter classes (`League\Flysystem\Adapter\Local`, `AwsS3Adapter`, ...), so
  on Flysystem 2/3 every storage type fails at container build (#91). Fix:
  version-aware check, or defer to core's `FilesystemAdapterMissing`.
- D2: `Configuration` key lists: `Dropbox` lists Flysystem-1 keys (`key`,
  `secret`, `app`); `AsyncAwsS3`, `Webdav` missing; `AwsS3` lacks
  `use_path_style_endpoint`; MySQL lacks `onlyTables`, `columnStatistics`,
  `socket`.
- D3: `protected static $defaultName` is deprecated in Symfony 6.1+ (use
  `#[AsCommand]`); Symfony 7 removed it.

| # | Kind | Decision | Release |
|---|---|---|---|
| #91 | Flysystem 2/3 | fix (D1, D2) | 3.3 |
| #90 | release / dead? | release 3.3.0 with Symfony 6/7/8 support | 3.3 |
| #89 | PHP 8.1 + Symfony 6 | fix: core ^3.3 allows symfony/process 6–8 | 3.3 |
| #88 | Flex config not loaded | fixed on master, unreleased; verify with a test | 3.3 |
| #84 | AsyncAws instead of AWS SDK | add `AsyncAwsS3` type (core 3.2+) | 3.3 |
| #78 | DSN user/password | answer: url-encode per RFC 3986 (Nyholm answered); verify decoding | 3.3 |

## M4: Close-out
- README badges in all three repos (core, laravel, symfony): replace stale
  badges with current ones that represent the project well (CI status from
  GitHub Actions, Packagist version, downloads, PHP version, license; drop
  dead services such as Travis/Scrutinizer/Coveralls). Do each framework
  repo's badges in its foundation step; core's here (Shawn, 2026-11-22).
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
- 2026-11-22: Every Laravel 7–11 release has an unpatched advisory
  (CVE-2026-128083 covers all below 12.71.0); Composer blocks them by
  default. laravel 2.1 still declares them (no narrowing in a minor) and CI
  tests them with `audit.block-insecure false`; Laravel 9 is tested on PHP 8.1
  (patched symfony/process 6.4.33 needs 8.1).
- 2026-11-22: laravel 2.1 narrows `illuminate/*` to ^7.0+ (PHP 7.4+): core
  ^3.3 needs CVE-patched symfony/process, which Laravel 5.5–6 cannot install
  (same rule as 3.2.1 dropping symfony/process 4.4). Documented in CHANGELOG.
- 2026-11-22: laravel 2.1 release order: tag core 3.3.0 → set laravel CI
  `CORE_CONSTRAINT` away from the branch (remove it) → green CI → merge
  laravel#138 → tag laravel 2.1.0 → close the issues listed in the PR.
- 2026-11-22: laravel 2.1 reads config at use time via `RuntimeConfig` (a
  core `Config` subclass) instead of changing command constructors; this fixes
  #78/#82/#115 without an API change.
- 2026-11-22: laravel versions: 2.1.0 on core ^3.2.1 (after 3.3.0: ^3.3), 3.0.0
  on core ^4.1. 3.0 drops the Lumen provider (Lumen is discontinued).
- Local clone of the laravel repo: `/home/shawn/src/backup-manager/laravel`.
- 2026-11-22: laravel 3.0 review fixes: prompts only on a terminal
  (Laravel's ConfiguresPrompts rule), confirmation defaults to no, discovery
  registers `BackupManagerServiceProvider`; `2.x` branch cut from 2.1.0.
- 2026-11-22: symfony 3.3 removes the bundle's build-time adapter check (it
  knew only Flysystem 1 classes); core reports a missing adapter when the
  storage is used. Tests use a hand-written TestKernel instead of
  nyholm/symfony-bundle-test so one suite runs on Symfony 3.4–8.
- 2026-11-22: symfony 3.3 ships native return types on four overridable
  methods (needed by Symfony 6–8), documented in Changelog (Shawn). Switching
  `BMBackupManagerExtension` to the DI component's `Extension` (http-kernel
  8.1 deprecates its own) narrows inherited API, so it waits for 4.0.
- 2026-11-22: symfony 3.3 floors raised to patched Symfony releases before
  tagging (security baseline; Symfony 3.4/4.4 have no patched releases),
  same rule as core 3.2.1. symfony 4.0: Symfony 6.4/7.4/8.1+ (8.0 has no
  patched release), DI-component Extension, final classes.
- 2026-11-22: Released core 3.3.0, 4.1.0 and laravel 2.1.0 (Shawn's go).
  laravel 2.1.0 was squash-merged with Co-authored-by trailers for nafiesl
  (#130) and martindrapeau (#135). All laravel PRs and issues closed with
  comments.
