<?php namespace BigName\BackupManager\Procedures;

use BigName\BackupManager\Config\ConfigFieldNotFound;
use BigName\BackupManager\Config\ConfigNotFoundForConnection;
use BigName\BackupManager\Tasks\Task;
use BigName\BackupManager\Databases\DatabaseProvider;
use BigName\BackupManager\Compressors\CompressorProvider;
use BigName\BackupManager\Filesystems\FilesystemProvider;
use BigName\BackupManager\Shell\ShellProcessor;

/**
 * Class Procedure
 * @package BigName\BackupManager\Procedures
 */
abstract class Procedure
{
    /**
     * @var Sequence
     */
    private $sequence;
    /**
     * @var FilesystemProvider
     */
    protected $filesystems;
    /**
     * @var DatabaseProvider
     */
    protected $databases;
    /**
     * @var CompressorProvider
     */
    protected $compressors;
    /**
     * @var ShellProcessor
     */
    protected $shellProcessor;

    /**
     * @param FilesystemProvider $filesystemProvider
     * @param DatabaseProvider $databaseProvider
     * @param CompressorProvider $compressorProvider
     * @param ShellProcessor $shellProcessor
     * @param Sequence $sequence
     */
    public function __construct(FilesystemProvider $filesystemProvider, DatabaseProvider $databaseProvider, CompressorProvider $compressorProvider, ShellProcessor $shellProcessor, Sequence $sequence)
    {
        $this->filesystems = $filesystemProvider;
        $this->databases = $databaseProvider;
        $this->compressors = $compressorProvider;
        $this->shellProcessor = $shellProcessor;
        $this->sequence = $sequence;
    }

    /**
     * @param Task $task
     */
    protected function add(Task $task)
    {
        $this->sequence->add($task);
    }

    /**
     * Execute the sequence.
     */
    protected function execute()
    {
        $this->sequence->execute();
    }
} 
