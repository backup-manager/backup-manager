# Database Backup Manager

[![Tests](https://github.com/backup-manager/backup-manager/actions/workflows/ci.yml/badge.svg)](https://github.com/backup-manager/backup-manager/actions/workflows/ci.yml)
[![Static analysis](https://github.com/backup-manager/backup-manager/actions/workflows/static.yml/badge.svg)](https://github.com/backup-manager/backup-manager/actions/workflows/static.yml)
[![Latest Version](https://img.shields.io/packagist/v/backup-manager/backup-manager)](https://packagist.org/packages/backup-manager/backup-manager)
[![PHP Version](https://img.shields.io/packagist/dependency-v/backup-manager/backup-manager/php)](https://packagist.org/packages/backup-manager/backup-manager)
[![Downloads](https://img.shields.io/packagist/dt/backup-manager/backup-manager)](https://packagist.org/packages/backup-manager/backup-manager/stats)
[![License](https://img.shields.io/packagist/l/backup-manager/backup-manager)](LICENSE)

Framework integrations: [Laravel](https://github.com/backup-manager/laravel) · [Symfony](https://github.com/backup-manager/symfony)

This package provides a framework-agnostic database backup manager for dumping to and restoring databases from S3, Google Cloud Storage, Dropbox, FTP, SFTP, WebDAV, and any other [Flysystem](https://flysystem.thephpleague.com/) filesystem.

- use version 4 for PHP 8.2+ with Flysystem 3 (active)
- use version 3 for PHP 7.4+ with Flysystem 1, 2 or 3 (security fixes until 2027-12-31)
- use version 2 for PHP 7.3 (unsupported)
- use version 1 for &lt;PHP 7.2 (unsupported)

See [CHANGELOG.md](CHANGELOG.md) for release notes and [UPGRADE.md](UPGRADE.md) for upgrading from 3.x.

[Watch a video tour](https://www.youtube.com/watch?v=vWXy0R8OavM) showing the Laravel driver in action to give you an idea what is possible.

- supports `MySQL` and `PostgreSQL`
- compress with `Gzip`
- framework-agnostic
- dead simple configuration
- [Laravel Driver](http://github.com/backup-manager/laravel)
- [Symfony Driver](http://github.com/backup-manager/symfony)

### Table of Contents

- [Database Backup Manager](#database-backup-manager)
    - [Table of Contents](#table-of-contents)
    - [Quick and Dirty](#quick-and-dirty)
    - [Requirements](#requirements)
    - [Installation](#installation)
    - [Usage](#usage)
    - [Contribution Guidelines](#contribution-guidelines)
    - [Maintainers](#maintainers)
    - [Backwards Compatibility Breaks](#backwards-compatibility-breaks)
    - [License](#license)

### Quick and Dirty

**Configure your databases.**

```php
// config/database.php
'development' => [
    'type' => 'mysql',
    'host' => 'localhost',
    'port' => '3306',
    'user' => 'root',
    'pass' => 'password',
    'database' => 'test',
    // If singleTransaction is set to true, the --single-transcation flag will be set.
    // This is useful on transactional databases like InnoDB.
    // http://dev.mysql.com/doc/refman/5.7/en/mysqldump.html#option_mysqldump_single-transaction
    'singleTransaction' => false,
    // Do not dump the given tables
    // Set only table names, without database name
    // Example: ['table1', 'table2']
    // http://dev.mysql.com/doc/refman/5.7/en/mysqldump.html#option_mysqldump_ignore-table
    'ignoreTables' => [],
    // connect through a unix socket; when set, host and port are not used (mysql only):
    // 'socket' => '/run/mysqld/mysqld.sock',
    // using ssl to connect to your database - active ssl-support (mysql only):
    'ssl'=>false,
    // add additional options to dump-command (like '--max-allowed-packet')
    'extraParams'=>null,
    // set to false to pass --column-statistics=0, needed when a MySQL 8 mysqldump
    // dumps from a server without column statistics. MariaDB's mysqldump rejects it.
    // 'columnStatistics' => false,
    // Dump only these tables (table names without the database name)
    // 'onlyTables' => ['table1', 'table2'],
],
'production' => [
    'type' => 'postgresql',
    'host' => 'localhost',
    'port' => '5432',
    'user' => 'postgres',
    'pass' => 'password',
    'database' => 'test',
],
```

**Configure your filesystems.**

```php
// config/storage.php
'local' => [
    'type' => 'Local',
    'root' => '/path/to/working/directory',
],
's3' => [
    'type' => 'AwsS3',
    'key'    => '',
    'secret' => '',
    'region' => 'us-east-1',
    'version' => 'latest',
    'bucket' => '',
    'root'   => '',
    'use_path_style_endpoint' => false,
],
'async-s3' => [
    'type' => 'AsyncAwsS3', // uses async-aws instead of the AWS SDK
    'key'    => '',
    'secret' => '',
    'region' => 'us-east-1',
    'bucket' => '',
    'root'   => '',
],
'gcs' => [
    'type' => 'Gcs',
    'projectId' => '',
    'keyFilePath' => '', // path to a service account JSON key; omit to use default credentials
    'bucket' => '',
    'root'   => '',
],
'dropbox' => [
    'type' => 'Dropbox',
    'token' => '',
    'root' => '',
],
'ftp' => [
    'type' => 'Ftp',
    'host' => '',
    'username' => '',
    'password' => '',
    'root' => '',
    'port' => 21,
    'passive' => true,
    'ssl' => true,
    'timeout' => 30,
],
'sftp' => [
    'type' => 'Sftp',
    'host' => '',
    'username' => '',
    'password' => '',
    'root' => '',
    'port' => 22,
    'timeout' => 10,
    'privateKey' => '',
],
'flysystem' => [
    'type' => 'Flysystem',
    'name' => 's3_backup', // key of a FilesystemOperator passed to new FlysystemFilesystem([...])
],
'doSpaces' => [
    'type' => 'AwsS3',
    'key' => '',
    'secret' => '',
    'region' => '',
    'bucket' => '',
    'root' => '',
    'endpoint' => '',
    'use_path_style_endpoint' => false,
],
'webdav' => [
    'type' => 'Webdav',
    'baseUri' => 'http://myserver.com',
    'userName' => '',
    'password' => '',
    'prefix' => '',
],
```

**Backup to / restore from any configured database.**

Backup the development database to `Amazon S3`. The S3 backup path will be `test/backup.sql.gz` in the end, when `gzip` is done with it.

```php
use BackupManager\Filesystems\Destination;

$manager = require 'bootstrap.php';
$manager->makeBackup()->run('development', [new Destination('s3', 'test/backup.sql')], 'gzip');
```

**Backup to / restore from any configured filesystem.**

Restore the database file `test/backup.sql.gz` from `Amazon S3` to the `development` database.

```php
$manager = require 'bootstrap.php';
$manager->makeRestore()->run('s3', 'test/backup.sql.gz', 'development', 'gzip');
```

> This package does not allow you to backup from one database type and restore to another. A MySQL dump is not compatible with PostgreSQL.

**Back up several databases in one run.**

Each call backs up one database connection, so loop over the ones you want:

```php
use BackupManager\Filesystems\Destination;

$manager = require 'bootstrap.php';
$date = date('Y-m-d-His');

foreach (['development', 'production'] as $database) {
    $manager->makeBackup()->run($database, [new Destination('s3', "{$database}/{$date}.sql")], 'gzip');
}
```

**The `local` filesystem is required.** Backups and restores use the `local` storage connection as a working directory, so your storage config needs a `local` entry and `LocalFilesystem` must be registered with the `FilesystemProvider`, as in the [example bootstrap](examples/standalone/bootstrap.php).

### Requirements

- PHP 8.2 or later
- [Flysystem](https://flysystem.thephpleague.com/) 3
- MySQL support requires `mysqldump` and `mysql` command-line binaries
- PostgreSQL support requires `pg_dump` and `psql` command-line binaries
- Gzip support requires the `gzip` command-line binary


### Installation

**Composer**

Run the following to include this via Composer

```shell
composer require backup-manager/backup-manager
```

Then, install the adapter package for each filesystem type you use:

| Type | Package |
|---|---|
| `Local` | included |
| `AwsS3` | `league/flysystem-aws-s3-v3` |
| `AsyncAwsS3` | `league/flysystem-async-aws-s3` |
| `Gcs` | `league/flysystem-google-cloud-storage` |
| `Dropbox` | `spatie/flysystem-dropbox` |
| `Ftp` | `league/flysystem-ftp` |
| `Sftp` | `league/flysystem-sftp-v3` |
| `Webdav` | `league/flysystem-webdav` |
| `Flysystem` | none: pass your own `FilesystemOperator` instances to `FlysystemFilesystem` |

For any other storage, construct the Flysystem adapter yourself and register it through the `Flysystem` type. A Flysystem `MountManager` can be registered the same way, with destination paths such as `upload://backup.sql`.

### Usage

Once installed, the package must be bootstrapped (initial configuration) before it can be used.

We've provided a native PHP example [here](https://github.com/backup-manager/backup-manager/tree/master/examples).

The required bootstrapping can [be found in the example here](https://github.com/backup-manager/backup-manager/blob/master/examples/standalone/bootstrap.php).


### Contribution Guidelines

See [docs/MAINTAINING.md](docs/MAINTAINING.md) for supported versions, the release procedure and the security baseline. Run the specs with `vendor/bin/phpspec run`. Static analysis: `phpstan analyze`, `psalm` and `php-cs-fixer fix --dry-run` (PSR-12).

When contributing please consider the following guidelines:

- Code style is PSR-12
    - Interfaces should NOT be suffixed with `Interface`, Traits should NOT be suffixed with `Trait`.
- All methods and classes must contain docblocks.
- Ensure that you submit tests that have minimal 100% coverage. Given the project's simplicity it just makes sense.
- When planning a pull-request to add new functionality, it may be wise to [submit a proposal](https://github.com/backup-manager/backup-manager/issues/new) to ensure compatibility with the project's goals.

### Maintainers

This package is maintained by [Shawn McCool](http://shawnmc.cool) and you!

### Backwards Compatibility Breaks

See [CHANGELOG.md](CHANGELOG.md).

### License

This package is licensed under the [MIT license](https://github.com/backup-manager/backup-manager/blob/master/LICENSE). Go wild.
