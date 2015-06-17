> **Note:** 1.0 is brand new. The Laravel driver has been removed from this repository and moved [to its own repository](http://github.com/backup-manager/laravel). Instructions for installing and configuring it can be found there. 

# Database Backup Manager 1.0

[![Latest Stable Version](https://poser.pugx.org/backup-manager/backup-manager/version.png)](https://packagist.org/packages/backup-manager/backup-manager)
[![License](https://poser.pugx.org/backup-manager/backup-manager/license.png)](https://packagist.org/packages/backup-manager/backup-manager)
[![Build Status](https://travis-ci.org/backup-manager/backup-manager.svg?branch=master)](https://travis-ci.org/backup-manager/backup-manager)
[![Coverage Status](https://coveralls.io/repos/backup-manager/backup-manager/badge.png?branch=master)](https://coveralls.io/r/backup-manager/backup-manager?branch=master)
[![Total Downloads](https://poser.pugx.org/backup-manager/backup-manager/downloads.png)](https://packagist.org/packages/backup-manager/backup-manager)

- supports `MySQL` and `PostgreSQL`
- compress with `Gzip`
- framework-agnostic
- dead simple configuration
- [Laravel Driver](http://github.com/backup-manager/laravel)

This package is completely framework agnostic. Mitchell has put together a [video tour](https://www.youtube.com/watch?v=vWXy0R8OavM) of Laravel integration, to give you an idea what is possible with this package.

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

This package is actively being developed and we would like to get feedback to improve it. [Please feel free to submit feedback.](https://github.com/backup-manager/backup-manager/issues/new)

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
    'region' => Aws\Common\Enum\Region::US_EAST_1,
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
$manager = require 'bootstrap.php';
$manager->makeBackup()->run('development', 's3', 'test/backup.sql', 'gzip');
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
composer require league/flysystem-aws-s3-v2

# to support dropbox
composer require league/flysystem-dropbox

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

This package is maintained by [Shawn McCool](http://shawnmc.cool), [Mitchell van Wijngaarden](http://kooding.nl), and Graham Campbell.

### License

This package is licensed under the [MIT license](https://github.com/backup-manager/backup-manager/blob/master/LICENSE).
