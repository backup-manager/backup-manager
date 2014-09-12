<?php namespace BigName\BackupManager;

use BigName\BackupManager\Procedures;
use Symfony\Component\Process\Process;
use BigName\BackupManager\Procedures\Sequence;
use BigName\BackupManager\Databases\DatabaseProvider;
use BigName\BackupManager\Filesystems\FilesystemProvider;
use BigName\BackupManager\Compressors\CompressorProvider;
use BigName\BackupManager\ShellProcessing\ShellProcessor;

/**
 * Class Manager
 * @package BigName\BackupManager
 */
class Manager
{
    /**
     * @var Filesystems\FilesystemProvider
     */
    private $filesystems;
    /**
     * @var Databases\DatabaseProvider
     */
    private $databases;
    /**
     * @var Compressors\CompressorProvider
     */
    private $compressors;

    /**
     * @param \BigName\BackupManager\Filesystems\FilesystemProvider $filesystems
     * @param \BigName\BackupManager\Databases\DatabaseProvider $databases
     * @param \BigName\BackupManager\Compressors\CompressorProvider $compressors
     */
    public function __construct(FilesystemProvider $filesystems, DatabaseProvider $databases, CompressorProvider $compressors)
    {
        $this->filesystems = $filesystems;
        $this->databases = $databases;
        $this->compressors = $compressors;
    }

    /**
     * @return Procedures\BackupProcedure
     */
    public function makeBackup()
    {
        return new Procedures\BackupProcedure(
            $this->filesystems,
            $this->databases,
            $this->compressors,
            $this->getShellProcessor()
        );
    }

    /**
     * @return Procedures\RestoreProcedure
     */
    public function makeRestore()
    {
        return new Procedures\RestoreProcedure(
            $this->filesystems,
            $this->databases,
            $this->compressors,
            $this->getShellProcessor()
        );
    }

    /**
     * @return ShellProcessing\ShellProcessor
     */
    protected function getShellProcessor()
    {
        return new ShellProcessor(new Process('', null, null, null, null));
    }
} 
