<?php

use McCool\DatabaseBackup\Commands\Mysql\Connection;
use McCool\DatabaseBackup\Commands\Mysql\DumpCommand;
use McCool\DatabaseBackup\ShellProcessor;
use Symfony\Component\Process\Process;

$path = 'test.sql'; // Destination path
$details = new Connection('localhost', '3306', 'username', 'password', 'database');
$command = new DumpCommand($path, $details);

$processor = new ShellProcessor(new Process(''));
$processor->process($command);

$procedure = '';

// Commands:

// php artisan
