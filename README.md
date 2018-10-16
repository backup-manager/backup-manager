# Database Backup Manager 1.2

[![Latest Stable Version](https://poser.pugx.org/backup-manager/backup-manager/version.png)](https://packagist.org/packages/backup-manager/backup-manager)
[![License](https://poser.pugx.org/backup-manager/backup-manager/license.png)](https://packagist.org/packages/backup-manager/backup-manager)
[![Build Status](https://travis-ci.org/backup-manager/backup-manager.svg?branch=master)](https://travis-ci.org/backup-manager/backup-manager)
[![Coverage Status](https://coveralls.io/repos/backup-manager/backup-manager/badge.svg?branch=master&service=github)](https://coveralls.io/github/backup-manager/backup-manager?branch=master)
[![Total Downloads](https://poser.pugx.org/backup-manager/backup-manager/downloads.png)](https://packagist.org/packages/backup-manager/backup-manager)

This package provides a framework-agnostic database backup manager for dumping to and restoring databases from S3, Dropbox, FTP, SFTP, and Rackspace Cloud.

[Watch a video tour](https://www.youtube.com/watch?v=vWXy0R8OavM) showing the Laravel driver in action to give you an idea what is possible.

- supports `MySQL` and `PostgreSQL`
- compress with `Gzip`
- framework-agnostic
- dead simple configuration
- [Laravel Driver](http://github.com/backup-manager/laravel)
- [Symfony Driver](http://github.com/backup-manager/symfony)

### Table of Contents

- [Stability Notice](#stability-notice)
- [Quick and Dirty](#quick-and-dirty)
- [Requirements](#requirements)
- [Installation](#installation)
- [Usage](#usage)
- [Contribution Guidelines](#contribution-guidelines)
- [Maintainers](#maintainers)
- [License](#license)

### Stability Notice

It's stable enough, you'll need to understand permissions.

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
],
'b2' => [
    'type' => 'B2',
    'key'    => '',
    'accountId' => '',
    'bucket' => '',
],
'gcs' => [
    'type' => 'Gcs',
    'key'    => '',
    'secret' => '',
    'version' => 'latest',
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
    'type' => 'DropboxV2',
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

### Requirements

- PHP 5.5
- MySQL support requires `mysqldump` and `mysql` command-line binaries
- PostgreSQL support requires `pg_dump` and `psql` command-line binaries
- Gzip support requires `gzip` and `gunzip` command-line binaries


### Installation

**Composer**

Run the following to include this via Composer

```shell
composer require backup-manager/backup-manager
```

Then, you'll need to select the appropriate packages for the adapters that you want to use.

```shell
# to support s3
composer require league/flysystem-aws-s3-v3

# to support b2
composer require mhetreramesh/flysystem-backblaze

# to support google cs
composer require league/flysystem-aws-s3-v2

# to support dropbox (api v2)
composer require srmklive/flysystem-dropbox-v2

# to support rackspace
composer require league/flysystem-rackspace

# to support sftp
composer require league/flysystem-sftp
```

### Usage

Once installed, the package must be bootstrapped (initial configuration) before it can be used. 

We've provided a native PHP example [here](https://github.com/backup-manager/backup-manager/tree/master/examples).

The required bootstrapping can [be found in the example here](https://github.com/backup-manager/backup-manager/blob/master/examples/standalone/bootstrap.php).

### Contribution Guidelines

We recommend using the vagrant configuration supplied with this package for development and contribution. Simply install VirtualBox, Vagrant, and Ansible then run `vagrant up` in the root folder. A virtualmachine specifically designed for development of the package will be built and launched for you.

When contributing please consider the following guidelines:

- please conform to the code style of the project, it's essentially PSR-2 with a few differences.
    1. The NOT operator when next to parenthesis should be surrounded by a single space. `if ( ! is_null(...)) {`.
    2. Interfaces should NOT be suffixed with `Interface`, Traits should NOT be suffixed with `Trait`.
- All methods and classes must contain docblocks.
- Ensure that you submit tests that have minimal 100% coverage.
- When planning a pull-request to add new functionality, it may be wise to [submit a proposal](https://github.com/backup-manager/backup-manager/issues/new) to ensure compatibility with the project's goals.

### Maintainers

This package is maintained by [Shawn McCool](http://shawnmc.cool), [Mitchell van Wijngaarden](http://blog.mitchellvanw.io), and you!

### License

This package is licensed under the [MIT license](https://github.com/backup-manager/backup-manager/blob/master/LICENSE).
