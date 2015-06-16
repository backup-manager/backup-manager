<?php namespace BackupManager;

use BackupManager\Procedures;
use Symfony\Component\Process\Process;
use BackupManager\Procedures\Sequence;
use BackupManager\Databases\DatabaseProvider;
use BackupManager\Filesystems\FilesystemProvider;
use BackupManager\Compressors\CompressorProvider;
use BackupManager\ShellProcessing\ShellProcessor;

/**
 * Class Manager
 * @package BackupManager
 */
class Manager {

    /** @var FilesystemProvider */
    private $filesystems;
    /** @var DatabaseProvider */
    private $databases;
    /** @var CompressorProvider */
    private $compressors;

    /**
     * @param \BackupManager\Filesystems\FilesystemProvider $filesystems
     * @param \BackupManager\Databases\DatabaseProvider $databases
     * @param \BackupManager\Compressors\CompressorProvider $compressors
     */
    public function __construct(FilesystemProvider $filesystems, DatabaseProvider $databases, CompressorProvider $compressors) {
        $this->filesystems = $filesystems;
        $this->databases = $databases;
        $this->compressors = $compressors;
    }

    /**
     * @return Procedures\BackupProcedure
     */
    public function makeBackup() {
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
    public function makeRestore() {
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
    protected function getShellProcessor() {
        return new ShellProcessor(new Process('', null, null, null, null));
    }
} 
