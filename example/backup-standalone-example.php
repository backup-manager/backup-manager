<?php

$mysqlConnectionDetails = new \McCool\DatabaseBackup\Mysql\MysqlConnectionDetails('localhost', '3306', 'username', 'password', 'database');
$s3ConnectionDetails = new \McCool\DatabaseBackup\S3\S3ConnectionDetails('key', 'secret', 'us-east-1', 'bucket', 'basePath');

$backupFilename = 'test.sql';

// this most point to your composer vendor autoloader
require '../vendor/autoload.php';

// dump the database, gzip it, upload it to S3, and clean up after ourselves
$shellProcessor = new \McCool\DatabaseBackup\CommandProcessor(new Symfony\Component\Process\Process(''));

$mysql = new \McCool\DatabaseBackup\Mysql\Mysql($shellProcessor, $mysqlConnectionDetails, $backupFilename);
$archiver = new \McCool\DatabaseBackup\Gzip\Gzip($shellProcessor);
$storer = new \McCool\DatabaseBackup\S3\S3(new \Aws\Common\Aws(), $s3ConnectionDetails);

$backup = new \McCool\DatabaseBackup\Procedures\BackupProcedure($dumper);
$backup->setArchiver($archiver);
$backup->setStorer($storer);

$backup->backup();
$backup->cleanup();
