<?php

require_once __DIR__.'/../vendor/autoload.php';

use BackupManager\Compressors\GzipCompressor;
use BackupManager\Config\Config;
use BackupManager\Databases\MysqlDatabase;
use BackupManager\File;
use BackupManager\Filesystems\FlysystemFilesystem;
use BackupManager\RemoteFile;
use BackupManager\Restore;
use BackupManager\Shell\ShellProcessor;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use League\Flysystem\MountManager;
use Symfony\Component\Process\Process;


$localFilesystem = new Filesystem(new Local(__DIR__.'/working'));
$otherLocalFilesystem = new Filesystem(new Local(__DIR__.'/working/other'));
$mountManager = new MountManager([
    'local' => $localFilesystem,
    'other_local' => $otherLocalFilesystem
]);

// Mysql Config
$config = new Config([
    'host' => 'localhost',
    'port' => '3306',
    'user' => 'homestead',
    'pass' => 'secret',
    'database' => 'gravity'
]);

$shell = new ShellProcessor(new Process('', null, null, null, null));

$mysql = new MysqlDatabase($shell, $config);
$files = new FlysystemFilesystem($mountManager);
$compressor = new GzipCompressor($shell);

$restore = new Restore($mysql, $files, $compressor);
$restore->run(new RemoteFile('other_local', new File('some1.sql.gz')));