# Database Backup Manager

[![Latest Stable Version](https://poser.pugx.org/mccool/database-backup/version.png)](https://packagist.org/packages/mccool/database-backup)
[![License](https://poser.pugx.org/mccool/database-backup/license.png)](https://packagist.org/packages/mccool/database-backup)
[![Build Status](https://travis-ci.org/heybigname/backup-manager.svg?branch=master)](https://travis-ci.org/heybigname/backup-manager)
[![Coverage Status](https://coveralls.io/repos/heybigname/backup-manager/badge.png?branch=master)](https://coveralls.io/r/heybigname/backup-manager?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/5e507053-58d7-4cff-b757-4202b021f9b0/mini.png)](https://insight.sensiolabs.com/projects/5e507053-58d7-4cff-b757-4202b021f9b0)
[![Total Downloads](https://poser.pugx.org/mccool/database-backup/downloads.png)](https://packagist.org/packages/mccool/database-backup)

- supports MySQL and PostgreSQL
- backup to or restore databases from AWS S3, Dropbox, FTP, SFTP, Rackspace Cloud, and WebDAV
- compress with Gzip
- framework-agnostic
- dead simple configuration
- optional integrations for MVC framework [Laravel](http://laravel.com)

### Full Disclosure

This initial release is likely to change given feedback from users. [Please feel free to submit feedback.](https://github.com/heybigname/backup-manager/issues/new)

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
    'working-path' => '/',
],
's3' => [
    'type' => 'AwsS3',
    'key'    => '',
    'secret' => '',
    'region' => Aws\Common\Enum\Region::US_EAST_1,
    'bucket' => '',
],
'rackspace' => [
    'type' => 'Rackspace',
    'username' => '',
    'password' => '',
    'container' => '',
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
use BigName\BackupManager\Manager;
$manager = new Manager('storage.php', 'database.php');
$manager->makeBackup()->run('development', 's3', 'test/backup.sql', 'gzip');
```

**Backup to / restore from any configured filesystem.**

```php
use BigName\BackupManager\Manager;
$manager = new Manager('storage.php', 'database.php');
$manager->makeRestore()->run('s3', 'test/backup.sql.gz', 'development', 'gzip');
```

> This package does not allow you to backup from one database type and restore to another.

### Installation

**Composer**

```JSON
"require": {
    "heybigname/backup-manager": "1.*"
}
```

### Requirements

- PHP 5.4
- MySQL support requires `mysqldump` and `mysql` command-line binaries
- PostgreSQL support requires `pg_dump` and `psql` command-line binaries
- Gzip support requires `gzip` and `gunzip` command-line binaries

### License

MIT
