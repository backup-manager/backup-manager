<?php

use McCool\DatabaseBackup\Commands\Mysql\Connection;
use McCool\DatabaseBackup\Commands\Mysql\DumpDatabase;
use McCool\DatabaseBackup\ShellProcessor;
use Symfony\Component\Process\Process;

$path = 'test.sql'; // Destination path
$details = new Connection('localhost', '3306', 'username', 'password', 'database');
$command = new DumpDatabase($path, $details);

$processor = new ShellProcessor(new Process(''));
$processor->process($command);

$procedure = '';

// Commands:

// php artisan
