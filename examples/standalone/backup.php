<?php

use BackupManager\Filesystems\Destination;

$manager = require 'bootstrap.php';
$manager
    ->makeBackup()
    ->run('development', [
        new Destination('local', 'test/backup.sql'),
        new Destination('s3', 'test/dump.sql')
    ], 'gzip');
