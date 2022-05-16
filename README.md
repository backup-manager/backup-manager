# Database Backup Manager

[![Build Status](https://github.com/fezfez/backup-manager/actions/workflows/continuous-integration.yml/badge.svg)](https://github.com/fezfez/backup-manager/actions/workflows/continuous-integration.yml)
[![Type Coverage](https://shepherd.dev/github/fezfez/backup-manager/coverage.svg)](https://shepherd.dev/github/fezfez/backup-manager)
[![Type Coverage](https://shepherd.dev/github/fezfez/backup-manager/level.svg)](https://shepherd.dev/github/fezfez/backup-manager)
[![Latest Stable Version](https://poser.pugx.org/fezfez/backup-manager/v/stable)](https://packagist.org/packages/fezfez/backup-manager)
[![License](https://poser.pugx.org/fezfez/backup-manager/license)](https://packagist.org/packages/fezfez/backup-manager)


This package provides a framework-agnostic database backup manager for dumping to and restoring databases from any file system.

- supports `MySQL` and `PostgreSQL`
- compress with `Gzip`
- framework-agnostic
- dead simple configuration

### Quick and Dirty

**Backup to / restore from any configured database.**

Backup the development database to `Amazon S3`. The S3 backup path will be `test/backup.sql.gz` in the end, when `gzip` is done with it.

```php
$local = new \League\Flysystem\Adapter\Local(getcwd() . '/data/');
$webdav = new \League\Flysystem\WebDAV\WebDAVAdapter(new \Sabre\DAV\Client([
    'baseUri' => getenv('WEBDAV_HOST'),
    'userName' => getenv('WEBDAV_username'),
    'password' => getenv('WEBDAV_password'),
]), 'remote.php/webdav/');

$manager = \Fezfez\BackupManager\BackupManager::create();
$manager->backup(
    new LeagueFilesystemAdapaterV1($local),
    new \Fezfez\BacpkupManager\Databases\MysqlDatabase(
        getenv('DB_HOST'),
        getenv('DB_PORT'),
        getenv('DB_USER'),
        getenv('DB_PASSWORD'),
        getenv('DB_DATABASE')
    ),
  [
    new \Fezfez\BackupManager\Filesystems\Destination(new LeagueFilesystemAdapaterV1($webdav), 'test/backup.sql')
  ],
  \Fezfez\BackupManager\Compressors\GzipCompressor::create()
);

$manager->restore(
    new LeagueFilesystemAdapaterV1($local),
    new LeagueFilesystemAdapaterV1($webdav),
    'test/backup.sql',
    new \Fezfez\BacpkupManager\Databases\MysqlDatabase(
        getenv('DB_HOST'),
        getenv('DB_PORT'),
        getenv('DB_USER'),
        getenv('DB_PASSWORD'),
        getenv('DB_DATABASE')
    ),
  \Fezfez\BackupManager\Compressors\GzipCompressor::create()
);
```

**Backup to / restore from any configured filesystem.**

Restore the database file `test/backup.sql.gz` from `Amazon S3` to the `development` database.

```php
$manager = require 'bootstrap.php';
$manager->makeRestore()->run('s3', 'test/backup.sql.gz', 'development', 'gzip');
```

> This package does not allow you to backup from one database type and restore to another. A MySQL dump is not compatible with PostgreSQL.

### Requirements

- PHP ^8.0
- MySQL support requires `mysqldump` and `mysql` command-line binaries
- PostgreSQL support requires `pg_dump` and `psql` command-line binaries
- Gzip support requires `gzip` and `gunzip` command-line binaries


### Installation

**Composer**

Run the following to include this via Composer

```shell
composer require fezfez/backup-manager
```

Then, you'll need to select the appropriate packages for the adapters that you want to use.

```shell
# to support league-flysystem:^1.0
composer require fezfez/backup-manager-league-flysystem-v1

# to support league-flysystem:^2.0
composer require fezfez/backup-manager-league-flysystem-v2

# to support league-flysystem:^3.0
composer require fezfez/backup-manager-league-flysystem-v3
```

### Usage

Once installed, the package must be bootstrapped (initial configuration) before it can be used.

We've provided a native PHP example [here](https://github.com/backup-manager/backup-manager/tree/master/examples).

The required bootstrapping can [be found in the example here](https://github.com/backup-manager/backup-manager/blob/master/examples/standalone/bootstrap.php).


### Contribution Guidelines

We recommend using the vagrant configuration supplied with this package for development and contribution. Simply install VirtualBox, Vagrant, and Ansible then run `vagrant up` in the root folder. A virtualmachine specifically designed for development of the package will be built and launched for you.

When contributing please consider the following guidelines:

- Code style is PSR-2
    - Interfaces should NOT be suffixed with `Interface`, Traits should NOT be suffixed with `Trait`.
- All methods and classes must contain docblocks.
- Ensure that you submit tests that have minimal 100% coverage. Given the project's simplicity it just makes sense.
- When planning a pull-request to add new functionality, it may be wise to [submit a proposal](https://github.com/backup-manager/backup-manager/issues/new) to ensure compatibility with the project's goals.


### Backwards Compatibility Breaks

### License

This package is licensed under the [MIT license](https://github.com/backup-manager/backup-manager/blob/master/LICENSE). Go wild.
