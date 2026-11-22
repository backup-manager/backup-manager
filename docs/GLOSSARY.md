# Glossary

Terms used in this repository, the framework packages and their docs.

- **Flysystem major**: the installed major version of `league/flysystem`
  (v1, v2 or v3). v2 and v3 share most class names; v1 differs. 4.x supports
  v3 only; 3.x supports all three.
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
  `AwsS3`, `Sftp`, ...), matched case-insensitively.
- **Version-aware factory** (3.x only): a filesystem factory that builds the
  adapter for whichever Flysystem major is installed.
- **Connection name**: the key of a config entry in `storage.php` or
  `database.php` (`local`, `s3`, `production`, ...). In the Laravel package,
  database connection names are Laravel's (`config/database.php`).
- **Public API**: every public class, interface, public/protected method and
  its signature, config key, and filesystem/database type. An incompatible
  change requires a major release.
- **Framework package**: `backup-manager/laravel` or `backup-manager/symfony`,
  which wire this library into a framework.
- **Runtime config** (Laravel package): a core `Config` whose entries are read
  from Laravel's configuration on every access, so configuration changed at
  runtime is used by providers that already exist.
- **Patched floor**: the lowest version a Composer constraint allows on each
  release line, set to the first release patched for known security
  advisories.
