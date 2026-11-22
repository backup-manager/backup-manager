# Database Backup Manager

![Packagist Version](https://img.shields.io/packagist/v/backup-manager/backup-manager)
![Packagist License](https://img.shields.io/packagist/l/backup-manager/backup-manager)
![Installs](https://img.shields.io/packagist/dt/backup-manager/backup-manager)

This package provides a framework-agnostic database backup manager for dumping to and restoring databases from S3, Google Cloud Storage, Dropbox, FTP, SFTP, WebDAV, and any other [Flysystem](https://flysystem.thephpleague.com/) filesystem.

- use version 3 for PHP 7.4+ with Flysystem 1, 2 or 3
- use version 2 for PHP 7.3
- use version 1 for &lt;PHP 7.2

See [CHANGELOG.md](CHANGELOG.md) for release notes.

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
'b2' => [
    'type' => 'B2',
    'key'    => '',
    'accountId' => '',
    'bucket' => '',
],
'async-s3' => [
    'type' => 'AsyncAwsS3', // Flysystem 2/3; uses async-aws instead of the AWS SDK
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
'rackspace' => [
    'type' => 'Rackspace',
    'username' => '',
    'key' => '',
    'container' => '',
    'zone' => '',
    'root' => '',
],
'dropbox' => [
    'type' => 'Dropbox',
    'token' => '',
    'key' => '',
    'secret' => '',
    'app' => '',
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
    'port' => 21,
    'timeout' => 10,
    'privateKey' => '',
],
'flysystem' => [
    'type' => 'Flysystem',
    'name' => 's3_backup',
    //'prefix' => 'upload',
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

- PHP 7.4 or later
- [Flysystem](https://flysystem.thephpleague.com/) 1, 2 or 3
- MySQL support requires `mysqldump` and `mysql` command-line binaries
- PostgreSQL support requires `pg_dump` and `psql` command-line binaries
- Gzip support requires `gzip` and `gunzip` command-line binaries


### Installation

**Composer**

Run the following to include this via Composer

```shell
composer require backup-manager/backup-manager
```

Then, install the adapter package for each filesystem type you use. The package depends on your Flysystem major version (`composer show league/flysystem`).

| Type | Flysystem 3 | Flysystem 2 | Flysystem 1 |
|---|---|---|---|
| `Local` | included | included | included |
| `AwsS3` | `league/flysystem-aws-s3-v3` | `league/flysystem-aws-s3-v3` | `league/flysystem-aws-s3-v3:^1.0` |
| `AsyncAwsS3` | `league/flysystem-async-aws-s3` | `league/flysystem-async-aws-s3` | not available |
| `Gcs` | `league/flysystem-google-cloud-storage` | `league/flysystem-google-cloud-storage:^2.0` | `superbalist/flysystem-google-storage` |
| `Dropbox` | `spatie/flysystem-dropbox` | `spatie/flysystem-dropbox:^2.0` | `spatie/flysystem-dropbox:^1.2` |
| `Ftp` | `league/flysystem-ftp` | `league/flysystem-ftp:^2.0` | included |
| `Sftp` | `league/flysystem-sftp-v3` | `league/flysystem-sftp:^2.0` | `league/flysystem-sftp:^1.0` |
| `Webdav` | `league/flysystem-webdav` | not available | `league/flysystem-webdav:^1.0` |
| `DropboxV2` (deprecated) | not available | not available | `srmklive/flysystem-dropbox-v2` |
| `Rackspace` (deprecated) | not available | not available | `league/flysystem-rackspace` |
| `B2` (deprecated) | not available | not available | `mhetreramesh/flysystem-backblaze` |

Every Flysystem 1 and 2 release is affected by a security advisory, and Composer refuses to install them by default. Use Flysystem 3.

### Usage

Once installed, the package must be bootstrapped (initial configuration) before it can be used.

We've provided a native PHP example [here](https://github.com/backup-manager/backup-manager/tree/master/examples).

The required bootstrapping can [be found in the example here](https://github.com/backup-manager/backup-manager/blob/master/examples/standalone/bootstrap.php).


### Contribution Guidelines

Run the specs with `vendor/bin/phpspec run`. `composer.json` installs the Flysystem 3 adapter packages for development; CI also runs the specs against Flysystem 1 and 2 using `.github/scripts/use-flysystem.sh`.

When contributing please consider the following guidelines:

- Code style is PSR-2
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
