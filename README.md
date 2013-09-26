Database Backup
===============

A framework-agnostic database backup package.

# Installation

## Laravel

1. add to composer.json

    "mccool/database-backup": "dev-master"

2. install dependency

    composer update

3. install configuration file

    php artisan config:publish mccool/database-backup

4. add service provider to config/app.php

    'McCool\DatabaseBackup\ServiceProviders\LaravelServiceProvider',

5. add key / secret to the config file in ```app/config/packages/mccool/database-backup/aws.php```

# Usage

## Laravel

**Get a List of Options**

    php artisan help db:backup

# License

MIT