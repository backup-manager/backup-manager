<?php namespace BigName\BackupManager;

use BigName\BackupManager\Config\Config;
use BigName\BackupManager\Databases\DatabaseProvider;
use BigName\BackupManager\Filesystems\FilesystemProvider;
use BigName\BackupManager\Compressors\CompressorProvider;
use BigName\BackupManager\ShellProcessing\ShellProcessor;
use Symfony\Component\Process\Process;
use BigName\BackupManager\Procedures\Sequence;
use BigName\BackupManager\Procedures\BackupProcedure;
use BigName\BackupManager\Procedures\RestoreProcedure;

class Manager
{
    private $storageConfig;
    private $databaseConfig;

    public function __construct($storageConfigPath, $databaseConfigPath)
    {
        $this->storageConfig = new Config($storageConfigPath);
        $this->databaseConfig = new Config($databaseConfigPath);
    }

    public function backup($database, $storage, $destinationPath, $compression = null)
    {
        $backup = new BackupProcedure(
            new FilesystemProvider($this->storageConfig),
            new DatabaseProvider($this->databaseConfig),
            new CompressorProvider,
            $this->getShellProcessor(),
            new Sequence
        );
        return $backup->run($database, $storage, $destinationPath, $compression);
    }

    public function restore($storage, $sourcePath, $database, $compression = null)
    {
        $restore = new RestoreProcedure(
            new FilesystemProvider($this->storageConfig),
            new DatabaseProvider($this->databaseConfig),
            new CompressorProvider,
            $this->getShellProcessor(),
            new Sequence
        );
        return $restore->run($storage, $sourcePath, $database, $compression);
    }

    private function getShellProcessor()
    {
        return new ShellProcessor(new Process(''));
    }
} 
