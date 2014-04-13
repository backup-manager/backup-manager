<?php namespace BigName\BackupManager;

use BigName\BackupManager\Config\Config;
use BigName\BackupManager\Databases\DatabaseProvider;
use BigName\BackupManager\Filesystems\FilesystemProvider;
use BigName\BackupManager\Compressors\CompressorProvider;
use BigName\BackupManager\Procedures\Sequence;
use BigName\BackupManager\Procedures\BackupProcedure;
use BigName\BackupManager\Procedures\RestoreProcedure;
use BigName\BackupManager\ShellProcessing\ShellProcessor;
use Symfony\Component\Process\Process;

/**
 * Class Manager
 * @package BigName\BackupManager
 */
class Manager
{
    /**
     * @var Config\Config
     */
    private $storage;
    /**
     * @var Config\Config
     */
    private $database;

    /**
     * @param $storage
     * @param $database
     */
    public function __construct($storage, $database)
    {

        $this->storage = new Config($storage);
        $this->database = new Config($database);
    }

    /**
     * @return BackupProcedure
     */
    public function makeBackup()
    {
        return new BackupProcedure(
            new FilesystemProvider($this->storage),
            new DatabaseProvider($this->database),
            new CompressorProvider,
            $this->getShellProcessor(),
            new Sequence
        );
    }

    /**
     * @return RestoreProcedure
     */
    public function makeRestore()
    {
        return new RestoreProcedure(
            new FilesystemProvider($this->storage),
            new DatabaseProvider($this->database),
            new CompressorProvider,
            $this->getShellProcessor(),
            new Sequence
        );
    }

    /**
     * @return ShellProcessor
     */
    private function getShellProcessor()
    {
        return new ShellProcessor(new Process(''));
    }
} 
