# Modernization campaign (2026-11)

Goal: bring backup-manager up to date under strict semver. Do everything that is
backward compatible in 3.x first, then make the 4.0.0 break, then update the
framework packages (`backup-manager/laravel`, `backup-manager/symfony`).

## Glossary

- **Flysystem major**: the installed major version of `league/flysystem` (v1, v2 or v3).
  v2 and v3 share most class names; v1 differs.
- **Flysystem instance**: an object from `league/flysystem` that performs file
  operations (`League\Flysystem\Filesystem` / `FilesystemInterface` in v1,
  `FilesystemOperator` in v2/v3).
- **Adapter package**: an optional Composer package that provides a Flysystem
  storage backend (e.g. `league/flysystem-aws-s3-v3`). The user installs it; this
  package only lists it under `suggest`.
- **Filesystem factory**: a class in `src/Filesystems/` implementing this
  package's `BackupManager\Filesystems\Filesystem` interface. It reports which
  **filesystem type** it handles and builds a Flysystem instance from config.
  (The interface is named `Filesystem` for historical reasons; it is a factory.)
- **Filesystem type**: the `type` value of a storage config entry (`Local`,
  `AwsS3`, `sftp`, ...), matched case-insensitively by a filesystem factory.
- **Connection name**: the key of a config entry in `storage.php` or
  `database.php` (`local`, `s3`, `production`, ...).
- **Version-aware factory**: a filesystem factory that builds the correct adapter
  for whichever Flysystem major is installed.
- **Public API**: every public class, interface, public/protected method and its
  signature, config key and filesystem/database type under `src/`. Changing it
  incompatibly requires a major release.

## Milestones

### M0: Foundation (no release) — merged #200
- This document.
- CI runs on every push and pull request; current runners and actions.
- `.gitattributes` export-ignore (reimplements #171, credit fezfez).

### M1: 3.2.0 (backward compatible) — released 2026-11-22
- Version-aware factories for Local, FTP, SFTP, AwsS3, WebDAV, Dropbox (spatie),
  and `FlysystemFilesystem`, so the already-declared `^2 || ^3` Flysystem support
  works. Fixes #186, #188, #198 (partly), #189 (partly). Reimplements #191–#194, #187.
- New `AsyncAwsS3` filesystem type for Flysystem v2/v3 (#168, credit Nyholm).
- New `GoogleCloudStorage` path for Flysystem v3 via `league/flysystem-google-cloud-storage`, if it fits under the existing `gcs` type.
- Clear exception when an adapter package is missing, naming the package to install (#189).
- Clearer `FilesystemTypeNotSupported` message listing registered types (#198).
- Transfer and delete failures raise exceptions instead of passing silently (#172).
- `pdo_mysql` accepted as a MySQL database type; `--column-statistics=0` opt-in via config (#195, credit meiyasan).
- Shell-escape the restore input path for MySQL.
- `symfony/process` widened to include `^7.0 || ^8.0`.
- CI matrix: Flysystem 1 on PHP 7.4–8.4, 2 on 7.4/8.1, 3 on 8.0–8.5, plus lowest deps.
- `CHANGELOG.md` (#169), README corrections, multiple-database example (#173).

### M2: 4.0.0 (breaking)
- PHP ≥ 8.2; `league/flysystem ^3` only; `symfony/process ^6.4 || ^7 || ^8`.
- Remove factories for dead backends/adapters: Rackspace, Dropbox (srmklive v2
  driver), Backblaze (mhetreramesh; B2 is reachable via the S3-compatible API),
  Superbalist GCS (replaced by the league GCS adapter).
- Keep the provider architecture and the `Filesystem` factory interface
  (decision 2026-11-22). `FlysystemFilesystem` is the main path for users who
  want to pass their own `FilesystemOperator`.
- Native parameter/return types throughout; `final` where appropriate.
- Static analysis tooling updated; baselines shrunk.
- `UPGRADE.md` 3.x → 4.0. Mines #185 (Nyholm), #196 (Jelle-S), #190/#197 (fezfez).

### M3: Framework packages, then close-out
- `backup-manager/laravel`, `backup-manager/symfony` compatible with 4.0.
- Every open PR and issue closed with a comment referencing what replaced it.
- Glossary moved to `docs/GLOSSARY.md`.

## PR and issue dispositions

| # | Author | Disposition |
|---|---|---|
| PR 195 | meiyasan | Reimplement in M1: alias yes; column-statistics opt-in (unconditional flag breaks MariaDB `mysqldump`). |
| PR 194 | meiyasan | Reimplement version-aware in M1; keep flat config shape (no new `connection` key). |
| PR 193 | meiyasan | Reimplement version-aware in M1 (as-is breaks Flysystem v1 users). |
| PR 192 | meiyasan | Reimplement version-aware in M1; flat config. |
| PR 191 | meiyasan | Incorporate in M1 (`suggest` the v3 SFTP package alongside v1). |
| PR 185 | Nyholm | Basis for M2. |
| PR 168 | Nyholm | Reimplement in M1 without the PHP floor bump. |
| Issue 199 | | Reply once 3.2.0 is released. |
| Issue 198, 188, 186 | | Fixed by M1. |
| Issue 189 | | M1: missing-adapter-package exception + README. |
| Issue 173 | | M1: README example. |
| Issue 172 | | M1. |
| Issue 169 | | M1: CHANGELOG. |
| Issue 167 | | Decline: a Docker image is out of scope for a library. |

Credit: merged PRs keep authorship; reimplementations carry
`Co-authored-by:` trailers and a CHANGELOG mention.

## Decisions log

- 2026-11-22: Authority: Claude pushes branches, merges PRs, comments on and
  closes PRs and issues; release tags only with Shawn's explicit go.
- 2026-11-22: 4.0 keeps providers (Nyholm's Flysystem-native proposal declined).
- 2026-11-22: CI is the test matrix; no local matrix script.
- 2026-11-22: `3.x` branch created from the 3.2.0 tag for 3.x fixes; master is 4.x.
- 2026-11-22: The Laravel and Symfony packages register `DropboxV2Filesystem`,
  `RackspaceFilesystem` (and Symfony `BackblazeFilesystem`) by class name, so
  they need a release before they can require ^4.0. They pin ^3.0 today, so
  4.0 does not reach their users until then.
- 2026-11-22: 4.0 drops the `FlysystemFilesystem` mount-manager prefix: a v3
  `MountManager` is itself a `FilesystemOperator` and can be registered by name.
- 2026-11-22: phpspec `^7.5 || ^8.0` covers PHP 7.4–8.5 (7.x up to 8.3,
  8.3.x from 8.2 through 8.5).
- 2026-11-22: GHSA-cxf4-7mrp-vvpr (low) flags `league/flysystem <= 3.35.2`, i.e.
  all of v1 and v2; phpseclib 2.x is also fully flagged. Composer blocks these by
  default, so users resolving 3.1.0 today get Flysystem 3.35.3+, the major 3.1.0
  fatals on. CI cells for v1/v2 use `--no-security-blocking`; consumer installs
  are unaffected. 4.0 floor: `league/flysystem ^3.35.3`.
