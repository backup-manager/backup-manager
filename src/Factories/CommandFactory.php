<?php namespace McCool\DatabaseBackup\Factories;

use McCool\DatabaseBackup\Commands\Database\Mysql\DumpDatabase;
use McCool\DatabaseBackup\Commands\Storage\SaveFile;
use McCool\DatabaseBackup\Config;
use McCool\DatabaseBackup\Connections\MysqlConnection;
use McCool\DatabaseBackup\ShellProcessor;
use Symfony\Component\Process\Process;

class CommandFactory
{
    /**
     * @var \McCool\DatabaseBackup\Factories\FilesystemFactory
     */
    private $filesystemFactory;
    /**
     * @var ArchiverFactory
     */
    private $archiverFactory;
    /**
     * @var \McCool\DatabaseBackup\Config
     */
    private $config;

    public function __construct(FilesystemFactory $filesystemFactory, ArchiverFactory $archiverFactory, Config $config)
    {
        $this->filesystemFactory = $filesystemFactory;
        $this->archiverFactory = $archiverFactory;
        $this->config = $config;
    }

    public function makeSaveFileCommand($connectionName, $sourcePath, $destinationPath)
    {
        /** @noinspection PhpParamsInspection */
        return new SaveFile($this->filesystemFactory->makeFilesystemFor($connectionName), $sourcePath, $destinationPath);
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
        return new DumpDatabase($mysql, new ShellProcessor(new Process('')), $destinationPath);
    }
} 
