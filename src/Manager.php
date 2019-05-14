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
 *
 * This is a facade class that gives consumers access to the simple backup and restore procedures.
 * This class can be copied and namespaced into your project, renamed, added to, modified, etc.
 * Once you've done that, your application can interact with the backup manager in one place only
 * and the rest of the system will interact with the new Manager-like construct that you created.
 *
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
