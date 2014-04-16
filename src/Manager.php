<?php namespace BigName\BackupManager;

use BigName\BackupManager\Databases;
use BigName\BackupManager\Filesystems;
use BigName\BackupManager\Compressors;
use BigName\BackupManager\Procedures\Sequence;
use BigName\BackupManager\Procedures;
use BigName\BackupManager\ShellProcessing\ShellProcessor;
use Symfony\Component\Process\Process;

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
     * @param Filesystems\FilesystemProvider $filesystems
     * @param Databases\DatabaseProvider $databases
     * @param Compressors\CompressorProvider $compressors
     */
    public function __construct(Filesystems\FilesystemProvider $filesystems, Databases\DatabaseProvider $databases, Compressors\CompressorProvider $compressors)
    {
        $this->filesystems = $filesystems;
        $this->databases = $databases;
        $this->compressors = $compressors;
    }

    /**
     * @return BackupProcedure
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
     * @return RestoreProcedure
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
        return new ShellProcessor(new Process(''));
    }
} 
