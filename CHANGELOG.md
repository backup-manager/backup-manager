# Changelog

All notable changes to this project are documented here. The format follows
[Keep a Changelog](https://keepachangelog.com/en/1.1.0/) and the project
adheres to [Semantic Versioning](https://semver.org/).

## [Unreleased]

## [3.2.0]

3.1.0 declared support for Flysystem 2 and 3 but only worked with Flysystem 1.
Composer now refuses to install any Flysystem 1.x or 2.x release by default
(security advisory GHSA-cxf4-7mrp-vvpr), so new installs of 3.1.0 resolved
Flysystem 3 and failed. This release makes every built-in filesystem work with
the Flysystem major you have installed.

### Added
- Flysystem 2 and 3 support for the Local, Ftp, Sftp, AwsS3, Gcs, Webdav and
  Dropbox filesystem types. Config shapes are unchanged. (#186, #188, #198;
  reimplements #187 and #191–#194 by @meiyasan, and the adapter work in #185
  by @Nyholm and #196 by @Jelle-S)
- `AsyncAwsS3` filesystem type, using `league/flysystem-async-aws-s3` instead of
  the full AWS SDK (Flysystem 2/3). (#168 by @Nyholm)
- `pdo_mysql` is accepted as a MySQL database type, and the MySQL
  `columnStatistics => false` option adds `--column-statistics=0`. (#195 by
  @meiyasan)
- `FilesystemAdapterMissing` is thrown when a filesystem type's adapter package
  is not installed, naming the package to install, instead of a "Class not
  found" fatal error. (#189)
- MySQL `onlyTables` option to dump only the listed tables. (from @fezfez's
  fork)
- `postgres` as a PostgreSQL database type. (#175 by @aalinia)
- `symfony/process` 7 and 8 are supported.
- Tested on PHP 7.4 through 8.5.

### Fixed
- A failed upload or download with Flysystem 1 now throws `FileTransferFailed`
  instead of reporting success. (#172, reported by @DjThossi)
- `TransferFile` and `DeleteFile` accept any Flysystem instance, including
  user-supplied `FilesystemOperator` implementations on Flysystem 2/3.
- `FlysystemFilesystem`'s `prefix` option works with the Flysystem 2/3
  `MountManager`.
- `Gcs` accepts the `projectId` key used by the sample config, as well as
  `project`. With Flysystem 2/3 it also accepts `root` as well as `prefix`.
- `Ftp` and `Sftp` accept numeric and boolean options given as strings (for
  example from environment variables) with Flysystem 2/3.
- The "filesystem type is not supported" message says how to register the
  missing filesystem. (#198)
- `symfony/process` 4.4 is accepted again; an unreleased change had narrowed
  the range.
- PHP 8.4 implicit-nullable parameter deprecation in `FlysystemFilesystem`.

### Changed
- Dist archives contain only the library (`src/`, `config/`, README, license).
  (#171 by @fezfez)

### Deprecated
- The `DropboxV2`, `Rackspace` and `B2` filesystem types. Their adapter
  packages only support Flysystem 1, which no longer receives security fixes.
  They will be removed in 4.0.

## [3.1.0] - 2022-07-05

### Added
- PHP 8.0 and 8.1 support. (#176 by @benr77)
- `symfony/process` 6 support.
- Declared support for Flysystem 2 and 3 (completed in 3.2.0).

## [3.0.1] - 2020-02-16

## [3.0] - 2020-02-16

### Removed
- Support for `symfony/process` < 3.

[Unreleased]: https://github.com/backup-manager/backup-manager/compare/3.2.0...HEAD
[3.2.0]: https://github.com/backup-manager/backup-manager/compare/3.1.0...3.2.0
[3.1.0]: https://github.com/backup-manager/backup-manager/compare/3.0.1...3.1.0
[3.0.1]: https://github.com/backup-manager/backup-manager/compare/3.0...3.0.1
[3.0]: https://github.com/backup-manager/backup-manager/compare/2.1.0...3.0
