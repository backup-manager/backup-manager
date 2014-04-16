<?php

$manager = require 'bootstrap.php';
$manager->makeBackup()->run('development', 's3', 'test/backup.sql', 'gzip');
