<?php namespace BigName\BackupManager;

use BigName\BackupManager\Procedures;
use Symfony\Component\Process\Process;
use BigName\BackupManager\Procedures\Sequence;
use BigName\BackupManager\Shell\ShellProcessor;
use BigName\BackupManager\Databases\DatabaseProvider;
use BigName\BackupManager\Filesystems\FilesystemProvider;
use BigName\BackupManager\Compressors\CompressorProvider;

/**
 * Class Manager
 * @package BigName\BackupManager
 */
class Manager
{
    /**
     * @var FilesystemProvider
     */
    private $filesystems;
    /**
     * @var DatabaseProvider
     */
    private $databases;
    /**
     * @var CompressorProvider
     */
    private $compressors;

    /**
     * @param FilesystemProvider $filesystems
     * @param DatabaseProvider $databases
     * @param CompressorProvider $compressors
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
            $this->getShellProcessor(),
            new Sequence
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
            $this->getShellProcessor(),
            new Sequence
        );
    }

    /**
     * @return ShellProcessor
     */
    protected function getShellProcessor()
    {
        return new ShellProcessor(new Process('', null, null, null, null));
    }
} 
