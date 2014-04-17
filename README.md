# Database Backup Manager

[![Latest Stable Version](https://poser.pugx.org/heybigname/backup-manager/version.png)](https://packagist.org/packages/heybigname/backup-manager)
[![License](https://poser.pugx.org/heybigname/backup-manager/license.png)](https://packagist.org/packages/heybigname/backup-manager)
[![Build Status](https://travis-ci.org/heybigname/backup-manager.svg?branch=master)](https://travis-ci.org/heybigname/backup-manager)
[![Coverage Status](https://coveralls.io/repos/heybigname/backup-manager/badge.png?branch=master)](https://coveralls.io/r/heybigname/backup-manager?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/5e507053-58d7-4cff-b757-4202b021f9b0/mini.png)](https://insight.sensiolabs.com/projects/5e507053-58d7-4cff-b757-4202b021f9b0)
[![Total Downloads](https://poser.pugx.org/heybigname/backup-manager/downloads.png)](https://packagist.org/packages/heybigname/backup-manager)

- supports `MySQL` and `PostgreSQL`
- backup to or restore databases from `AWS S3`, `Dropbox`, `FTP`, `SFTP` and `Rackspace Cloud`
- compress with `Gzip`
- framework-agnostic
- dead simple configuration
- optional integrations for MVC framework [Laravel](http://laravel.com)

### Table of Contents

- [Stability Notice](#stability-notice)
- [Quick and Dirty](#quick-and-dirty)
- [Requirements](#requirements)
- [Installation](#installation)
- [Integrations](#integrations)
    - [Laravel](#laravel)
- [Maintainers](#maintainers)
- [License](#license)

### Stability Notice

This isn't a `1.0` release.

This initial release is _VERY_ likely to change given feedback from users. [Please feel free to submit feedback.](https://github.com/heybigname/backup-manager/issues/new)

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
    'root' => '/',
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
    'password' => '',
    'container' => '',
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

```php
$manager = require 'bootstrap.php';
$manager->makeBackup()->run('development', 's3', 'test/backup.sql', 'gzip');
```

**Backup to / restore from any configured filesystem.**

```php
$manager = require 'bootstrap.php';
$manager->makeRestore()->run('s3', 'test/backup.sql.gz', 'development', 'gzip');
```

> This package does not allow you to backup from one database type and restore to another.

### Requirements

- PHP 5.4
- MySQL support requires `mysqldump` and `mysql` command-line binaries
- PostgreSQL support requires `pg_dump` and `psql` command-line binaries
- Gzip support requires `gzip` and `gunzip` command-line binaries

### Installation

**Composer**

1. Add the package to "require" in composer.json

```JSON
"require": {
    "heybigname/backup-manager": "1.*"
}
```

2. Update your composer packages.

`composer update`

### Integrations

The backup manager is easy to integrate into your favorite frameworks. We've included Laravel integration. We're definitely accepting pull-requests.

#### Laravel

To install into a Laravel project, first do the composer install then add the following class to your config/app.php service providers list.

```php
'BigName\BackupManager\Integrations\Laravel\BackupManagerServiceProvider',
```

Then, publish and modify the configuration file to suit your needs.

`php artisan config:publish heybigname/backup-manager`

**IoC Resolution**

`Manager` can be automatically resolved through constructor injection thanks to Laravel's IoC container.

```php
use BigName\BackupManager\Manager;

public function __construct(Manager $manager)
{
    $this->manager = $manager;
}
```

It can also be resolved manually from the container.

```php
$manager = App::make('BigName\BackupManager\Manager');
```

**Artisan Commands**

There are three commands available `manager:backup`, `manager:restore` and `manager:list`.

All will prompt you with simple questions to successfully execute the command.

### Maintainers

This package is maintained by Mitchell van Wijngaarden and Shawn McCool of [Big Name](http://heybigname.com)

### License

The MIT License (MIT)

Copyright (c) 2014 Big Name

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
