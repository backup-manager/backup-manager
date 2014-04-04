<?php namespace BigName\DatabaseBackup\Commands;

use BigName\DatabaseBackup\Commands\Archiving\GzipFile;
use BigName\DatabaseBackup\Commands\Database\Mysql\DumpDatabase;
use BigName\DatabaseBackup\Commands\Storage\DeleteFile;
use BigName\DatabaseBackup\Commands\Storage\SaveFile;
use BigName\DatabaseBackup\Config;
use BigName\DatabaseBackup\Connections\MysqlConnection;
use BigName\DatabaseBackup\Filesystems\FilesystemProvider;
use BigName\DatabaseBackup\ShellProcessor;
use Symfony\Component\Process\Process;

class CommandFactory
{
    /**
     * @var \BigName\DatabaseBackup\Filesystems\FilesystemProvider
     */
    private $filesystemProvider;
    /**
     * @var \BigName\DatabaseBackup\Config
     */
    private $config;

    public function __construct(FilesystemProvider $filesystemProvider, Config $config)
    {
        $this->filesystemProvider = $filesystemProvider;
        $this->config = $config;
    }

    public function makeSaveFileCommand($connectionName, $sourcePath, $destinationPath)
    {
        /** @noinspection PhpParamsInspection */
        return new SaveFile($this->filesystemProvider->getForConnection($connectionName), $sourcePath, $destinationPath);
    }

    public function makeDeleteFileCommand($connectionName, $filePath)
    {
        /** @noinspection PhpParamsInspection */
        return new DeleteFile($this->filesystemProvider->getForConnection($connectionName), $filePath);
    }

    public function makeDumpDatabaseCommand($databaseName, $destinationPath)
    {
        $mysql = new MysqlConnection(
            $this->config->get($databaseName, 'host'),
            $this->config->get($databaseName, 'port'),
            $this->config->get($databaseName, 'user'),
            $this->config->get($databaseName, 'pass'),
            $this->config->get($databaseName, 'database')
        );
        return new DumpDatabase($mysql, $this->getShellProcessor(), $destinationPath);
    }

    public function makeZipFileCommand($sourcePath)
    {
        return new GzipFile($this->getShellProcessor(), $sourcePath);
    }

    private function getShellProcessor()
    {
        return new ShellProcessor(new Process(''));
    }
} 
