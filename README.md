Database Backup
===============

A framework-agnostic database backup package.

**Drivers:** At this moment the package supports MySQL, gzip, and Amazon S3. However, it's modular and could be extended to support much more.

**Frameworks:** This package doesn't require a framework, but a Laravel service provider and Artisan command are made available for convenience.

Note: be aware that this package uses ```mysqldump``` for MySQL backups.

# Example

Laravel users can run the following command if they'd like to backup the db, gzip it, upload it to s3, and remove the local backup file:

```PHP
php artisan db:backup --s3-bucket=whatever --s3-path=/this/is/optional/ --cleanup --gzip
```

Non-Laravel users can look at the Usage section below.

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

**Dump the database to app/storage/dumps/databasename_timestamp.sql**

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

**Choose a specific filename other than the default (default is 'Y-m-d_H-i-s' ). Note, do not include the file extension .sql, we will do that for you**

```SHELL
php artisan db:backup --filename=my_project_backup
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
$shellProcessor = new McCool\DatabaseBackup\Processors\ShellProcessor(new Symfony\Component\Process\Process(''));
$dumper = new McCool\DatabaseBackup\Dumpers\MysqlDumper($shellProcessor, 'localhost', 3306, 'username', 'password', 'test_db', 'backup/test.sql');

$backup = new McCool\DatabaseBackup\BackupProcedure($dumper);
$backup->backup();

// dump the database to backup/test.sql and gzip it
$shellProcessor = new McCool\DatabaseBackup\Processors\ShellProcessor(new Symfony\Component\Process\Process(''));
$dumper   = new McCool\DatabaseBackup\Dumpers\MysqlDumper($shellProcessor, 'localhost', 3306, 'username', 'password', 'test_db', 'backup/test.sql');
$archiver = new McCool\DatabaseBackup\Archivers\GzipArchiver($shellProcessor);

$backup = new McCool\DatabaseBackup\BackupProcedure($dumper);
$backup->setArchiver($archiver);

$backup->backup();

// dump the database to backup/test.sql, gzip it, upload it to S3, and clean up after ourselves
$shellProcessor = new McCool\DatabaseBackup\Processors\ShellProcessor(new Symfony\Component\Process\Process(''));
$dumper   = new McCool\DatabaseBackup\Dumpers\MysqlDumper($shellProcessor, 'localhost', 3306, 'username', 'password', 'test_db', 'backup/test.sql');
$archiver = new McCool\DatabaseBackup\Archivers\GzipArchiver($shellProcessor);
$storer   = new McCool\DatabaseBackup\Storers\S3Storer($awsKey, $awsSecret, 'us-east-1', $bucket, $s3Path);

$backup = new McCool\DatabaseBackup\BackupProcedure($dumper);
$backup->setArchiver($archiver);
$backup->setStorer($storer);

$backup->backup();
$backup->cleanup();
```

# License

MIT
