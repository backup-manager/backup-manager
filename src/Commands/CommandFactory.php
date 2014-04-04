<?php namespace BigName\DatabaseBackup\Commands;

use BigName\DatabaseBackup\Commands\Archiving\GzipFile;
use BigName\DatabaseBackup\Commands\Database\Mysql\DumpDatabase;
use BigName\DatabaseBackup\Commands\Storage\DeleteFile;
use BigName\DatabaseBackup\Commands\Storage\SaveFile;
use BigName\DatabaseBackup\Config;
use BigName\DatabaseBackup\Connections\MysqlConnection;
use BigName\DatabaseBackup\Filesystems\FilesystemProvider;
use BigName\DatabaseBackup\ShellProcessing\ShellProcessor;
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
    private $databaseConfig;

    public function __construct(FilesystemProvider $filesystemProvider, Config $databaseConfig)
    {
        $this->filesystemProvider = $filesystemProvider;
        $this->databaseConfig = $databaseConfig;
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
            $this->databaseConfig->get($databaseName, 'host'),
            $this->databaseConfig->get($databaseName, 'port'),
            $this->databaseConfig->get($databaseName, 'user'),
            $this->databaseConfig->get($databaseName, 'pass'),
            $this->databaseConfig->get($databaseName, 'database')
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
