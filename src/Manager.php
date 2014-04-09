<?php namespace BigName\DatabaseBackup;

use BigName\DatabaseBackup\Config\Config;
use BigName\DatabaseBackup\Databases\DatabaseProvider;
use BigName\DatabaseBackup\Filesystems\FilesystemProvider;
use BigName\DatabaseBackup\Compressors\CompressorProvider;
use BigName\DatabaseBackup\ShellProcessing\ShellProcessor;
use Symfony\Component\Process\Process;
use BigName\DatabaseBackup\Procedures\Sequence;
use BigName\DatabaseBackup\Procedures\BackupProcedure;
use BigName\DatabaseBackup\Procedures\RestoreProcedure;

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
