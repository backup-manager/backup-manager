# Database Backup Manager

[![Latest Stable Version](https://poser.pugx.org/heybigname/backup-manager/version.png)](https://packagist.org/packages/heybigname/backup-manager)
[![License](https://poser.pugx.org/heybigname/backup-manager/license.png)](https://packagist.org/packages/heybigname/backup-manager)
[![Build Status](https://travis-ci.org/heybigname/backup-manager.svg?branch=master)](https://travis-ci.org/heybigname/backup-manager)
[![Coverage Status](https://coveralls.io/repos/heybigname/backup-manager/badge.png?branch=master)](https://coveralls.io/r/heybigname/backup-manager?branch=master)
[![Total Downloads](https://poser.pugx.org/heybigname/backup-manager/downloads.png)](https://packagist.org/packages/heybigname/backup-manager)

- supports `MySQL` and `PostgreSQL`
- compress with `Gzip`
- framework-agnostic
- dead simple configuration
- optional integrations for MVC framework [Laravel](http://laravel.com)

This package is completely framework agnostic. Mitchell has put together a [video tour](https://www.youtube.com/watch?v=vWXy0R8OavM) of Laravel integration, to give you an idea what is possible with this package.

### Table of Contents

- [Stability Notice](#stability-notice)
- [Quick and Dirty](#quick-and-dirty)
- [Requirements](#requirements)
- [Installation](#installation)
- [Usage](#usage)
- [Integrations](#integrations)
    - [Laravel](#laravel)
- [Contribution Guidelines](#contribution-guidelines)
- [Maintainers](#maintainers)
- [License](#license)

### Stability Notice

It's stable enough, you'll need to understand permissions.

This package is actively being developed and we would like to get feedback to improve it. [Please feel free to submit feedback.](https://github.com/heybigname/backup-manager/issues/new)

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

- PHP 5.4
- MySQL support requires `mysqldump` and `mysql` command-line binaries
- PostgreSQL support requires `pg_dump` and `psql` command-line binaries
- Gzip support requires `gzip` and `gunzip` command-line binaries


### Installation

**Composer**

Run the following to include this via Composer

```shell
composer require heybigname/backup-manager
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

Once installed, the package must be bootstrapped (initial configuration) before it can be used. If you're using Laravel then skip directly to the [Laravel integration section](#laravel).

We've provided a native PHP example [here](https://github.com/heybigname/backup-manager/tree/master/examples).

The required bootstrapping can [be found in the example here](https://github.com/heybigname/backup-manager/blob/master/examples/standalone/bootstrap.php).

### Integrations

The backup manager is easy to integrate into your favorite frameworks. We've included Laravel integration. We're definitely accepting pull-requests.

#### Laravel

To install into a Laravel project, first do the composer install then add the following class to your config/app.php service providers list.

```php
'BigName\BackupManager\Integrations\Laravel\BackupManagerServiceProvider',
```

Then, publish and modify the configuration file to suit your needs.

`php artisan config:publish heybigname/backup-manager --path=vendor/heybigname/backup-manager/config`

The Backup Manager will make use of Laravel's database configuration.

**IoC Resolution**

`Manager` can be automatically resolved through constructor injection thanks to Laravel's IoC container.

```php
use BigName\BackupManager\Manager;

public function __construct(Manager $manager) {
    $this->manager = $manager;
}
```

It can also be resolved manually from the container.

```php
$manager = App::make('BigName\BackupManager\Manager');
```

**Artisan Commands**

There are three commands available `db:backup`, `db:restore` and `db:list`.

All will prompt you with simple questions to successfully execute the command.

### Contribution Guidelines

We recommend using the vagrant configuration supplied with this package for development and contribution. Simply install VirtualBox, Vagrant, and Ansible then run `vagrant up` in the root folder. A virtualmachine specifically designed for development of the package will be built and launched for you.

When contributing please consider the following guidelines:

- please conform to the code style of the project, it's essentially PSR-2 with a few differences.
    1. The NOT operator when next to parenthesis should be surrounded by a single space. `if ( ! is_null(...)) {`.
    2. Interfaces should NOT be suffixed with `Interface`, Traits should NOT be suffixed with `Trait`.
- All methods and classes must contain docblocks.
- Ensure that you submit tests that have minimal 100% coverage.
- When planning a pull-request to add new functionality, it may be wise to [submit a proposal](https://github.com/heybigname/backup-manager/issues/new) to ensure compatibility with the project's goals.

### Maintainers

This package is maintained by [Mitchell van Wijngaarden](http://kooding.nl) and [Shawn McCool](http://shawnmc.cool) of [Big Name](http://heybigname.com)

### License

This package is licensed under the [MIT license](https://github.com/heybigname/backup-manager/blob/master/LICENSE).
