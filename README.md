Database Backup
===============

A framework-agnostic database backup package.

Be aware that this package uses ```mysqldump``` for MySQL backups.

# Requirements

* 5.4 (would openly accept pull requests to lower to 5.3)

# Installation

## Laravel

1. add to composer.json

    ```JSON
    "mccool/database-backup": "dev-master"
    ```
2. install dependency

    ```SHELL
    composer update
    ```
3. install configuration file

    ```SHELL
    php artisan config:publish mccool/database-backup
    ```
4. add service provider to config/app.php

    ```PHP
    'McCool\DatabaseBackup\ServiceProviders\LaravelServiceProvider',
    ```
5. add key / secret to the config file in ```app/config/packages/mccool/database-backup/aws.php```

## Native PHP

1. add to composer.json

    ```JSON
    "mccool/database-backup": "dev-master"
    ```
2. install dependency

    ```SHELL
    composer update
    ```
3. make sure that your app requires the composer autoloader

    ```PHP
    require '../vendor/autoload.php';
    ```

# Usage

## Laravel

**Dump the database to app/storage/databasename_timestamp.sql**

```SHELL
php artisan db:backup
```

**Store the database to backups/databasename_timestamp.sql**

```SHELL
php artisan db:backup --local-path=backups
```

**Gzip the database.**

```SHELL
php artisan db:backup --gzip
```

**Choose a database to dump other than the default (names are configured in Laravel's config/database.php).**

```SHELL
php artisan db:backup --database=otherdatabaseconnection
```

**Upload the backup to S3**

```SHELL
php artisan db:backup --s3-bucket=whatever --s3-path=/this/is/optional/
```

**Cleanup file when we're done**

```SHELL
php artisan db:backup --s3-bucket=whatever --s3-path=/this/is/optional/ --cleanup
```

## Native PHP

```PHP
<?php

require '../vendor/autoload.php';

// dump the database to backup/test.sql
$dumper = new McCool\DatabaseBackup\Dumpers\MysqlDumper(new McCool\DatabaseBackup\Processors\ShellProcessor, 'localhost', 3306, 'username', 'password', 'test_db', 'backup/test.sql');

$backup = new McCool\DatabaseBackup\BackupProcedure($dumper);
$backup->backup();

// dump the database to backup/test.sql and gzip it
$dumper   = new McCool\DatabaseBackup\Dumpers\MysqlDumper(new McCool\DatabaseBackup\Processors\ShellProcessor, 'localhost', 3306, 'username', 'password', 'test_db', 'backup/test.sql');
$archiver = new McCool\DatabaseBackup\Archivers\GzipArchiver(new McCool\DatabaseBackup\Processors\ShellProcessor);

$backup = new McCool\DatabaseBackup\BackupProcedure($dumper);
$backup->setArchiver($archiver);

$backup->backup();

// dump the database to backup/test.sql, gzip it, upload it to S3, and clean up after ourselves
$dumper   = new McCool\DatabaseBackup\Dumpers\MysqlDumper(new McCool\DatabaseBackup\Processors\ShellProcessor, 'localhost', 3306, 'username', 'password', 'test_db', 'backup/test.sql');
$archiver = new McCool\DatabaseBackup\Archivers\GzipArchiver(new McCool\DatabaseBackup\Processors\ShellProcessor);
$storer   = new McCool\DatabaseBackup\Storers\S3Storer($awsKey, $awsSecret, 'us-east-1', $bucket, $s3Path);

$backup = new McCool\DatabaseBackup\BackupProcedure($dumper);
$backup->setArchiver($archiver);
$backup->setStorer($storer);

$backup->backup();
$backup->cleanup();
```

# License

MIT